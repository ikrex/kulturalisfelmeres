<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\TemporarySurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Mail\SurveyCompletedMail;

class SurveyController extends Controller
{
    /**
     * Megjeleníti a kérdőív formot.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showForm(Request $request)
    {
        // Megnézzük, hogy van-e már uuid a kérésben
        $uuid = $request->query('uuid');
        $temporarySurvey = null;

        // Ha van uuid, megpróbáljuk betölteni az ideiglenes adatokat
        if ($uuid) {
            $temporarySurvey = TemporarySurvey::where('uuid', $uuid)
                ->where('is_completed', false)
                ->first();
        }

        return view('survey.form', compact('temporarySurvey', 'uuid'));
    }

    /**
     * Feldolgozza a beküldött kérdőívet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'uuid' => 'required|string|uuid',
            'institution_name' => 'required|string|max:255',
            'event_software' => 'nullable|string|max:255',
            'statistics_issues' => 'nullable|string',
            'communication_issues' => 'nullable|string',
            'event_transparency' => 'nullable|string',
            'want_help' => 'required|string|in:igen,nem,bizonytalan',
            'contact' => 'nullable|string|max:255',
            // Új mezők validációja
            'info_flow_issues' => 'nullable|string',
            'info_flow_issues_other_text' => 'nullable|string',
            'event_tracking_benefits' => 'nullable|string',
            'event_tracking_benefits_other_text' => 'nullable|string',
            'stats_benefits' => 'nullable|string',
            'stats_benefits_other_text' => 'nullable|string',
        ]);

        $validated['ip_address'] = $request->ip();

        // Létrehozzuk a végleges kérdőív bejegyzést
        $survey = Survey::create($validated);

        // Frissítjük az ideiglenes kérdőívet, jelezve, hogy befejezték
        if ($request->input('uuid')) {
            TemporarySurvey::where('uuid', $request->input('uuid'))
                ->update(['is_completed' => true]);
        }

        // Email küldése a befejezett kérdőívről
        try {
            Mail::to('illeskalman77@gmail.com')->send(new SurveyCompletedMail($survey));
        } catch (\Exception $e) {
            Log::error('Hiba a kitöltés értesítő email küldésekor: ' . $e->getMessage());
        }


        return redirect()
            ->route('survey.thanks')
            ->with('success', 'Köszönjük a kérdőív kitöltését!');
    }

    /**
     * Megjeleníti a köszönő oldalt.
     *
     * @return \Illuminate\View\View
     */
    public function showThanks()
    {
        return view('survey.thanks');
    }

    /**
     * Admin felület - Kitöltött kérdőívek listázása.
     *
     * @return \Illuminate\View\View
     */
    public function adminIndex()
    {
        $this->checkAdminAccess();

        $surveys = Survey::latest()->paginate(20);

        return view('admin.surveys.index', compact('surveys'));
    }

    /**
     * Admin felület - Kitöltött kérdőív részletes megtekintése.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function adminShow($id)
    {
        $this->checkAdminAccess();

        $survey = Survey::findOrFail($id);

        return view('admin.surveys.show', compact('survey'));
    }

    /**
     * Ellenőrzi, hogy a felhasználó admin-e.
     *
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function checkAdminAccess()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }
    }

    /**
     * Ideiglenes kérdőív létrehozása vagy frissítése.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTemporary(Request $request)
    {
        // Ellenőrizzük, hogy van-e már uuid
        $uuid = $request->input('uuid');

        if (!$uuid) {
            // Ha nincs uuid, akkor új kérdőív kezdődött
            $uuid = (string) Str::uuid();
        }

        // IP cím lekérdezése
        $ipAddress = $request->ip();

        try {
            // Ideiglenes rekord létrehozása, ha nem létezik a TemporarySurvey modell
            if (!class_exists('App\Models\TemporarySurvey')) {
                // Ha a modell nem létezik, akkor egyszerűen visszaadjuk az UUID-t
                return response()->json([
                    'success' => true,
                    'uuid' => $uuid,
                    'message' => 'Test mode: Model not exists but UUID generated'
                ]);
            }

            // Ideiglenes rekord mentése vagy frissítése
            $temporarySurvey = TemporarySurvey::updateOrCreate(
                ['uuid' => $uuid],
                [
                    'institution_name' => $request->input('institution_name'),
                    'event_software' => $request->input('event_software'),
                    'statistics_issues' => $request->input('statistics_issues'),
                    'communication_issues' => $request->input('communication_issues'),
                    'event_transparency' => $request->input('event_transparency'),
                    'want_help' => $request->input('want_help'),
                    'contact' => $request->input('contact'),
                    // Új mezők hozzáadása
                    'info_flow_issues' => $request->input('info_flow_issues'),
                    'info_flow_issues_other_text' => $request->input('info_flow_issues_other_text'),
                    'event_tracking_benefits' => $request->input('event_tracking_benefits'),
                    'event_tracking_benefits_other_text' => $request->input('event_tracking_benefits_other_text'),
                    'stats_benefits' => $request->input('stats_benefits'),
                    'stats_benefits_other_text' => $request->input('stats_benefits_other_text'),
                    'ip_address' => $ipAddress,
                    'is_completed' => false,
                ]
            );

            return response()->json([
                'success' => true,
                'uuid' => $uuid,
                'message' => 'Adatok ideiglenesen mentve'
            ]);
        } catch (\Exception $e) {
            // Naplózzuk a hibát, ha beállított a naplózás
            if (function_exists('info')) {
                info('Hiba az ideiglenes mentés során: ' . $e->getMessage());
            }

            return response()->json([
                'success' => false,
                'message' => 'Hiba történt az adatok mentése közben: ' . $e->getMessage()
            ], 500);
        }
    }
}
