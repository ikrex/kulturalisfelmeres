<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\TemporarySurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
        ]);

        $validated['ip_address'] = $request->ip();

        // Létrehozzuk a végleges kérdőív bejegyzést
        Survey::create($validated);

        // Frissítjük az ideiglenes kérdőívet, jelezve, hogy befejezték
        if ($request->input('uuid')) {
            TemporarySurvey::where('uuid', $request->input('uuid'))
                ->update(['is_completed' => true]);
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
}
