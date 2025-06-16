<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConferenceController extends Controller
{
    /**
     * Konferencia részvételi válasz oldal
     */
    public function response(Request $request, Survey $survey, $token)
    {
        // Token ellenőrzése
        $expectedToken = sha1($survey->uuid . $survey->contact);
        if ($token !== $expectedToken) {
            abort(403, 'Érvénytelen token.');
        }

        $decline = $request->has('decline');

        return view('conference.response', compact('survey', 'token', 'decline'));
    }

    /**
     * Konferencia részvételi válasz feldolgozása
     */
    public function submitResponse(Request $request, Survey $survey, $token)
    {
        // Token ellenőrzése
        $expectedToken = sha1($survey->uuid . $survey->contact);
        if ($token !== $expectedToken) {
            abort(403, 'Érvénytelen token.');
        }

        $request->validate([
            'attendance' => 'required|boolean',
            'attendees' => 'nullable|integer|min:1|max:20'
        ]);

        try {
            $survey->update([
                'conference_attendance' => $request->attendance,
                'conference_attendees' => $request->attendance ? $request->attendees : null,
                'conference_response_at' => now()
            ]);

            $message = $request->attendance
                ? 'Köszönjük! Részvételi szándékukat rögzítettük.'
                : 'Köszönjük a visszajelzést!';

            return view('conference.thank-you', compact('survey', 'message'));

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Konferencia válasz feldolgozási hiba: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Hiba történt a válasz feldolgozása során.');
        }
    }

    /**
     * Kulturális intézmények és tracking kódok összekapcsolása
     */
    public function linkCulturalInstitutions()
    {
        // Cultural institutions tábla alapján linkeljük a surveys táblával
        $surveys = Survey::all();
        $matchCount = 0;

        foreach ($surveys as $survey) {
            // Próbáljuk megtalálni a cultural_institutions táblában
            $culturalInstitution = \App\Models\CulturalInstitution::where('email', $survey->contact)
                ->orWhere('name', 'LIKE', '%' . $survey->institution_name . '%')
                ->first();

            if ($culturalInstitution) {
                // Itt lehetne tracking kód alapú azonosítást is beépíteni
                $matchCount++;
                \Illuminate\Support\Facades\Log::info("Összekapcsolva: {$survey->institution_name} <-> {$culturalInstitution->name}");
            }
        }

        return response()->json([
            'success' => true,
            'matched_count' => $matchCount,
            'total_surveys' => $surveys->count()
        ]);
    }
}
