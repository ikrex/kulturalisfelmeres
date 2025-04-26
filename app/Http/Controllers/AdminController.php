<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\TemporarySurvey;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        // Ellenőrizzük, hogy admin felhasználó-e
        if (!Auth::user()->user_group === 'admin') {
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

        // Napi statisztikák az elmúlt 30 napra - vonaldiagramhoz
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $dailySurveys = Survey::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Dátumtömb létrehozása
        $chartLabels = [];
        $chartData = [];

        // Minden napra kiszámítjuk az értéket
        $currentDate = Carbon::parse($startDate);

        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $chartLabels[] = $currentDate->format('m.d');
            $chartData[] = $dailySurveys[$dateStr] ?? 0;

            $currentDate->addDay();
        }

        return view('admin.dashboard', compact(
            'completedSurveysCount',
            'inProgressSurveysCount',
            'usersCount',
            'recentSurveys',
            'inProgressSurveys',
            'chartLabels',
            'chartData'
        ));
    }

    /**
     * Surveys - Kérdőívek listája
     */
    public function surveys()
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $surveys = Survey::latest()->paginate(15);

        return view('admin.surveys.index', compact('surveys'));
    }

    /**
     * Survey - Egy kérdőív részletes nézete
     */
    public function showSurvey($id)
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $survey = Survey::findOrFail($id);

        return view('admin.surveys.show', compact('survey'));
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

        return view('admin.temporary_surveys.index', compact('surveys'));
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

        return view('admin.temporary_surveys.show', compact('survey'));
    }

    /**
     * Users - Felhasználók listája
     */
    public function users()
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $users = User::latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * User - Egy felhasználó részletes nézete
     */
    public function showUser($id)
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Export - Kérdőívek exportálása CSV formátumban
     */
    public function exportSurveys()
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $surveys = Survey::all();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="surveys_export_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($surveys) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Fejlécek
            fputcsv($file, [
                'ID',
                'Intézmény neve',
                'Rendezvény szoftver',
                'Statisztikai problémák',
                'Kommunikációs problémák',
                'Rendezvény átláthatóság',
                'Segítséget szeretne',
                'Kapcsolattartó',
                'IP cím',
                'Létrehozva',
                'Információáramlás problémák',
                'Információáramlás egyéb szöveg',
                'Rendezvény követés előnyei',
                'Rendezvény követés egyéb szöveg',
                'Statisztikai előnyök',
                'Statisztikai előnyök egyéb szöveg'
            ]);

            foreach ($surveys as $survey) {
                fputcsv($file, [
                    $survey->id,
                    $survey->institution_name,
                    $survey->event_software,
                    $survey->statistics_issues,
                    $survey->communication_issues,
                    $survey->event_transparency,
                    $survey->want_help,
                    $survey->contact,
                    $survey->ip_address,
                    $survey->created_at,
                    $survey->info_flow_issues,
                    $survey->info_flow_issues_other_text,
                    $survey->event_tracking_benefits,
                    $survey->event_tracking_benefits_other_text,
                    $survey->stats_benefits,
                    $survey->stats_benefits_other_text
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Statistics - Kérdőív statisztikák
     */
    public function statistics()
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        // Havi statisztikák
        $monthlySurveys = Survey::select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.statistics', compact('monthlySurveys'));
    }

    /**
     * Settings - Admin beállítások
     */
    public function settings()
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        return view('admin.settings');
    }

    /**
     * Update Settings - Admin beállítások frissítése
     */
    public function updateSettings(Request $request)
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        // Validálás és mentés
        $request->validate([
            'site_title' => 'required|string|max:255',
            'email_notifications' => 'boolean',
            // További beállítások validálása
        ]);

        // Itt lehet menteni a beállításokat az adatbázisba vagy konfigurációs fájlba

        return redirect()->route('admin.settings')->with('success', 'Beállítások sikeresen frissítve!');
    }

    /**
     * Delete Survey - Kérdőív törlése
     */
    public function destroySurvey($id)
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $survey = Survey::findOrFail($id);
        $survey->delete();

        return redirect()->route('admin.surveys')->with('success', 'Kérdőív sikeresen törölve!');
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

    /**
     * Make Admin - Felhasználó admin jogosultságúvá tétele
     */
    public function makeAdmin($id)
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $user = User::findOrFail($id);
        $user->user_group = 'admin';
        $user->save();

        return redirect()->route('admin.users.show', $user->id)->with('success', 'A felhasználó sikeresen admin jogosultságot kapott!');
    }

    /**
     * Remove Admin - Felhasználó admin jogosultságának visszavonása
     */
    public function removeAdmin($id)
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $user = User::findOrFail($id);

        // Ellenőrizzük, hogy ez nem az utolsó admin-e
        $adminCount = User::where('user_group', 'admin')->count();

        if ($adminCount <= 1 && $user->user_group === 'admin') {
            return redirect()->route('admin.users.show', $user->id)->with('error', 'Az utolsó admin jogosultságát nem lehet elvenni!');
        }

        $user->user_group = 'user';
        $user->save();

        return redirect()->route('admin.users.show', $user->id)->with('success', 'Az admin jogosultság sikeresen visszavonva!');
    }

    /**
     * Admin ellenőrzése
     */
    private function isAdmin()
    {
        return Auth::user() && Auth::user()->user_group === 'admin';
    }
}
