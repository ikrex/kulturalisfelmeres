<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\TemporarySurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\CulturalInstitution; // Importáljuk a modellt


use App\Mail\SurveyCompletedMail;

class SurveyController extends Controller
{
/**
 * Megjeleníti a kérdőív formot.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
 */
public function showForm(Request $request)
{
    // Megnézzük, hogy van-e már uuid a kérésben
    $uuid = $request->query('uuid');
    $temporarySurvey = null;

    // Követőkód kezelése
    $trackingCode = $request->query('code');
    $institution = null;

    // Ha van követőkód, megpróbáljuk betölteni az intézményt
    if ($trackingCode) {
        $institution = CulturalInstitution::where('tracking_code', $trackingCode)->first();

        // Ha az intézmény létezik és már kitöltötte a kérdőívet, átirányítjuk a köszönő oldalra
        if ($institution && $institution->survey_completed) {
            return redirect()
                ->route('survey.thanks')
                ->with('info', 'Ezt a kérdőívet már korábban kitöltötte. Köszönjük részvételét!');
        }

        // Ellenőrizzük, hogy van-e már befejezett (kitöltött) survey ezzel az email címmel
        if ($institution && $institution->email) {
            $completedSurvey = Survey::where('contact', $institution->email)->first();
            if ($completedSurvey) {
                // Frissítjük az intézményt, jelezve, hogy kitöltötte a kérdőívet
                $institution->survey_completed = true;
                $institution->save();

                return redirect()
                    ->route('survey.thanks')
                    ->with('info', 'Ezzel az email címmel már kitöltötték a kérdőívet. Köszönjük részvételét!');
            }
        }
    }

    // Ha van uuid, megpróbáljuk betölteni az ideiglenes adatokat
    if ($uuid) {
        $temporarySurvey = TemporarySurvey::where('uuid', $uuid)
            ->where('is_completed', false)
            ->first();

        // Ha van ideiglenes kérdőív és követőkód is, ellenőrizzük, hogy nem töltötte-e már ki
        if ($temporarySurvey && $trackingCode) {
            // Ellenőrizzük, hogy a táblában van-e tracking_code mező
            $tempSurveyColumns = \Schema::getColumnListing('temporary_surveys');

            // Ha van tracking_code mező és az ideiglenes kérdőívhez már rögzítve van, frissítjük
            if (in_array('tracking_code', $tempSurveyColumns) && !$temporarySurvey->tracking_code) {
                $temporarySurvey->tracking_code = $trackingCode;
                $temporarySurvey->save();
            }
        }
    }

    return view('survey.form', compact('temporarySurvey', 'uuid', 'trackingCode', 'institution'));
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
            'tracking_code' => 'nullable|string', // Követőkód validációja
        ]);

        $validated['ip_address'] = $request->ip();

        // Ellenőrizzük, hogy van-e követőkód és az intézmény már kitöltötte-e a kérdőívet
        if ($request->has('tracking_code') && !empty($request->input('tracking_code'))) {
            $institution = CulturalInstitution::where('tracking_code', $request->input('tracking_code'))->first();

            if ($institution && $institution->survey_completed) {
                return redirect()
                    ->route('survey.thanks')
                    ->with('info', 'Ezt a kérdőívet már korábban kitöltötte. Köszönjük részvételét!');
            }
        }


// UUID lekérése és ellenőrzése - még a végleges kérdőív létrehozása előtt töröljük az ideiglenes rekordot
$uuid = $request->input('uuid');
if ($uuid) {
    try {
        // Az ideiglenes rekord keresése és törlése
        $temporarySurvey = TemporarySurvey::where('uuid', $uuid)->first();

        if ($temporarySurvey) {
            // Törlés előtt naplózzuk az azonosítókat
            Log::info('Ideiglenes kérdőív törlése folyamatban', [
                'id' => $temporarySurvey->id,
                'uuid' => $temporarySurvey->uuid,
                'institution_name' => $temporarySurvey->institution_name
            ]);

            // Törlés
            $deleted = $temporarySurvey->delete();

            // Naplózzuk a törlés eredményét
            if ($deleted) {
                Log::info('Ideiglenes kérdőív sikeresen törölve UUID alapján', ['uuid' => $uuid]);
            } else {
                Log::warning('Ideiglenes kérdőív törlése nem sikerült UUID alapján', ['uuid' => $uuid]);
            }
        } else {
            Log::warning('Nem található ideiglenes kérdőív a megadott UUID-val', ['uuid' => $uuid]);
        }
    } catch (\Exception $e) {
        Log::error('Hiba az ideiglenes kérdőív törlésekor: ' . $e->getMessage(), [
            'uuid' => $uuid,
            'exception' => get_class($e),
            'trace' => $e->getTraceAsString()
        ]);
    }
}




        // Létrehozzuk a végleges kérdőív bejegyzést
        $survey = Survey::create($validated);

        // Ha van követőkód, frissítjük az intézményt, hogy kitöltötte a kérdőívet
        if ($request->has('tracking_code') && !empty($request->input('tracking_code'))) {
            try {
                CulturalInstitution::where('tracking_code', $request->input('tracking_code'))
                    ->update(['survey_completed' => true]);

                Log::info('Intézmény kitöltési státusz frissítve', [
                    'tracking_code' => $request->input('tracking_code'),
                    'survey_id' => $survey->id
                ]);

                // Töröljük az ideiglenes kérdőíveket ezzel a követőkóddal
                $tempSurveyColumns = \Schema::getColumnListing('temporary_surveys');
                if (in_array('tracking_code', $tempSurveyColumns)) {
                    TemporarySurvey::where('tracking_code', $request->input('tracking_code'))->delete();
                    Log::info('Ideiglenes kérdőívek törölve a követőkód alapján', [
                        'tracking_code' => $request->input('tracking_code')
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Hiba az intézmény kitöltési státuszának frissítésekor: ' . $e->getMessage(), [
                    'tracking_code' => $request->input('tracking_code')
                ]);
            }
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
