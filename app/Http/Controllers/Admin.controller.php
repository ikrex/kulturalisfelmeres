<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\TemporarySurvey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Admin irányítópult megjelenítése.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Ellenőrizzük, hogy admin felhasználó-e
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        // Lekérdezzük a szükséges adatokat
        $completedSurveysCount = Survey::count();
        $inProgressSurveysCount = TemporarySurvey::where('is_completed', false)->count();
        $usersCount = User::count();

        // Legutóbbi kérdőívek
        $recentSurveys = Survey::latest()->take(5)->get();

        // Folyamatban lévő kitöltések
        $inProgressSurveys = TemporarySurvey::where('is_completed', false)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'completedSurveysCount',
            'inProgressSurveysCount',
            'usersCount',
            'recentSurveys',
            'inProgressSurveys'
        ));
    }
}
