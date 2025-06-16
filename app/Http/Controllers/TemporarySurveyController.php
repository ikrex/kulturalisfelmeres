<?php

namespace App\Http\Controllers;

use App\Models\TemporarySurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class TemporarySurveyController extends Controller
{
    /**
     * Ideiglenes kérdőív létrehozása vagy frissítése.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTemporary(Request $request)
    {
        // CSRF ellenőrzés kikapcsolása erre a metódusra, ha szükséges
        // $this->middleware('csrf')->except('saveTemporary');

        // Ellenőrizzük, hogy van-e már uuid
        $uuid = $request->input('uuid');

        if (!$uuid) {
            // Ha nincs uuid, akkor új kérdőív kezdődött
            $uuid = (string) Str::uuid();
        }

        // IP cím lekérdezése
        $ipAddress = $request->ip();

        // Debug log
        Log::info('Ideiglenes mentés kérés érkezett', [
            'uuid' => $uuid,
            'ip' => $ipAddress,
            'user_agent' => $request->userAgent(),
        ]);

        try {
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
            Log::error('Hiba az ideiglenes mentés során', [
                'error' => $e->getMessage(),
                'uuid' => $uuid
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba történt az adatok mentése közben: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin felületen az ideiglenes kérdőívek listázása.
     *
     * @return \Illuminate\View\View
     */
    public function adminIndex()
    {
        $query = TemporarySurvey::query();

        // Szűrés a befejezett/folyamatban lévő státusz alapján
        if (request()->has('completed')) {
            $query->where('is_completed', true);
        } else {
            $query->where('is_completed', false);
        }

        // Keresés
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('institution_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('uuid', 'like', "%{$search}%");
            });
        }

        $temporarySurveys = $query->latest()->paginate(20);

        return view('admin.temporary-surveys.index', compact('temporarySurveys'));
    }

    /**
     * Admin felületen egy ideiglenes kérdőív részletes megtekintése.
     *
     * @param  string  $uuid
     * @return \Illuminate\View\View
     */
    public function adminShow($uuid)
    {
        $temporarySurvey = TemporarySurvey::where('uuid', $uuid)->firstOrFail();
        $completedSurvey = $temporarySurvey->completedSurvey;

        return view('admin.temporary-surveys.show', compact('temporarySurvey', 'completedSurvey'));
    }

/**
 * Temporary Surveys - Folyamatban lévő kitöltések listája
 */
public function temporarySurveys()
{
    if (!Auth::user()->user_group === 'admin') {
        abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
    }

    $surveys = TemporarySurvey::where('is_completed', false)
        ->latest()
        ->paginate(15);

    return view('admin.temporary-surveys.index', compact('surveys'));
}

/**
 * Temporary Survey - Egy folyamatban lévő kitöltés részletes nézete
 */
public function showTemporarySurvey($id)
{
    if (!Auth::user()->user_group === 'admin') {
        abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
    }

    $survey = TemporarySurvey::findOrFail($id);

    return view('admin.temporary-surveys.show', compact('survey'));
}

/**
 * Delete Temporary Survey - Ideiglenes kérdőív törlése
 */
public function destroyTemporarySurvey($id)
{
    if (!Auth::user()->user_group === 'admin') {
        abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
    }

    $survey = TemporarySurvey::findOrFail($id);
    $survey->delete();

    return redirect()->route('admin.temporary-surveys')->with('success', 'Ideiglenes kérdőív sikeresen törölve!');
}


}
