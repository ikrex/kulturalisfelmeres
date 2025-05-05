<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CulturalInstitution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    /**
     * Konstruktor - jogosultság ellenőrzés minden metódusra
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->user_group !== 'admin') {
                abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
            }
            return $next($request);
        });
    }

    /**
     * Emailküldő felület megjelenítése
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $institutions = CulturalInstitution::all();
        $notCompletedCount = CulturalInstitution::where('survey_completed', false)->count();

        return view('admin.emails.index', compact('institutions', 'notCompletedCount'));
    }

    /**
     * Email küldése egy intézménynek
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendToOne(Request $request, $id)
    {
        $institution = CulturalInstitution::findOrFail($id);

        // Ha nincs még követőkódja, akkor generálunk egyet
        if (empty($institution->tracking_code)) {
            $institution->tracking_code = CulturalInstitution::generateTrackingCode();
            $institution->save();
        }

        try {
            // Email küldése
            Mail::send('emails.survey', ['institution' => $institution], function ($message) use ($institution) {
                $message->to($institution->email, $institution->name);
                $message->subject('Felmérés művelődési intézmények működéséről – Ingyenes visszajelzés a kitöltőknek');
            });

            return redirect()->route('admin.emails.index')
                ->with('success', "Sikeres email küldés a következő intézménynek: {$institution->name}");
        } catch (\Exception $e) {
            Log::error('Hiba történt az email küldése során: ' . $e->getMessage(), [
                'institution_id' => $institution->id,
                'institution_name' => $institution->name
            ]);

            return redirect()->route('admin.emails.index')
                ->with('error', "Hiba történt az email küldése során: {$e->getMessage()}");
        }
    }

    /**
     * Email küldése minden intézménynek, aki még nem töltötte ki a kérdőívet
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendToNotCompleted(Request $request)
    {
        $institutions = CulturalInstitution::where('survey_completed', false)->get();
        $successCount = 0;
        $errorCount = 0;

        foreach ($institutions as $institution) {
            // Ha nincs még követőkódja, akkor generálunk egyet
            if (empty($institution->tracking_code)) {
                $institution->tracking_code = CulturalInstitution::generateTrackingCode();
                $institution->save();
            }

            try {
                // Email küldése
                Mail::send('emails.survey', ['institution' => $institution], function ($message) use ($institution) {
                    $message->to($institution->email, $institution->name);
                    $message->subject('Felmérés művelődési intézmények működéséről – Ingyenes visszajelzés a kitöltőknek');
                });

                $successCount++;
            } catch (\Exception $e) {
                Log::error('Hiba történt az email küldése során: ' . $e->getMessage(), [
                    'institution_id' => $institution->id,
                    'institution_name' => $institution->name
                ]);

                $errorCount++;
            }
        }

        if ($errorCount > 0) {
            return redirect()->route('admin.emails.index')
                ->with('warning', "Email küldés eredménye: {$successCount} sikeres, {$errorCount} sikertelen küldés.");
        } else {
            return redirect()->route('admin.emails.index')
                ->with('success', "Összesen {$successCount} intézménynek sikeresen kiküldve az email.");
        }
    }
}
