<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\TemporarySurvey;
use App\Models\Response;
use App\Models\User;
use App\Models\CulturalInstitution;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Mail;


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
    public function exportSurveysCSV()
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
 * Export - Kérdőívek exportálása Excel formátumban összesítő adatokkal
 */
public function exportSurveys()
{
    if (!Auth::user()->user_group === 'admin') {
        abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
    }

    $surveys = Survey::all();
    $fileName = 'surveys_export_' . date('Y-m-d') . '.xlsx';

    $spreadsheet = new Spreadsheet();

    // 1. Első munkalap - Nyers adatok
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Kitöltések');

    // Fejlécek
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Intézmény neve');
    $sheet->setCellValue('C1', 'Rendezvény szoftver');
    $sheet->setCellValue('D1', 'Statisztikai problémák');
    $sheet->setCellValue('E1', 'Kommunikációs problémák');
    $sheet->setCellValue('F1', 'Rendezvény átláthatóság');
    $sheet->setCellValue('G1', 'Segítséget szeretne');
    $sheet->setCellValue('H1', 'Kapcsolattartó');
    $sheet->setCellValue('I1', 'IP cím');
    $sheet->setCellValue('J1', 'Létrehozva');
    $sheet->setCellValue('K1', 'Információáramlás problémák');
    $sheet->setCellValue('L1', 'Információáramlás egyéb szöveg');
    $sheet->setCellValue('M1', 'Rendezvény követés előnyei');
    $sheet->setCellValue('N1', 'Rendezvény követés egyéb szöveg');
    $sheet->setCellValue('O1', 'Statisztikai előnyök');
    $sheet->setCellValue('P1', 'Statisztikai előnyök egyéb szöveg');

    // Adatok
    $row = 2;
    foreach ($surveys as $survey) {
        $sheet->setCellValue('A' . $row, $survey->id);
        $sheet->setCellValue('B' . $row, $survey->institution_name);
        $sheet->setCellValue('C' . $row, $survey->event_software);
        $sheet->setCellValue('D' . $row, $survey->statistics_issues);
        $sheet->setCellValue('E' . $row, $survey->communication_issues);
        $sheet->setCellValue('F' . $row, $survey->event_transparency);
        $sheet->setCellValue('G' . $row, $survey->want_help);
        $sheet->setCellValue('H' . $row, $survey->contact);
        $sheet->setCellValue('I' . $row, $survey->ip_address);
        $sheet->setCellValue('J' . $row, $survey->created_at);
        $sheet->setCellValue('K' . $row, $survey->info_flow_issues);
        $sheet->setCellValue('L' . $row, $survey->info_flow_issues_other_text);
        $sheet->setCellValue('M' . $row, $survey->event_tracking_benefits);
        $sheet->setCellValue('N' . $row, $survey->event_tracking_benefits_other_text);
        $sheet->setCellValue('O' . $row, $survey->stats_benefits);
        $sheet->setCellValue('P' . $row, $survey->stats_benefits_other_text);

        $row++;
    }

    // Táblázat formázása - Oszlopok automatikus méretezése
    foreach(range('A','P') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Fejléc formázása - félkövér betűtípus
    $sheet->getStyle('A1:P1')->getFont()->setBold(true);

    // 2. Második munkalap - Összesítés
    $summarySheet = $spreadsheet->createSheet();
    $summarySheet->setTitle('Összesítés');

    // Cím
    $summarySheet->setCellValue('A1', 'ÖSSZESÍTŐ STATISZTIKÁK');
    $summarySheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $summarySheet->mergeCells('A1:C1');
    $summarySheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Alapstatisztikák
    $summarySheet->setCellValue('A3', 'Kitöltő intézmények száma:');
    $summarySheet->setCellValue('B3', $surveys->count());
    $summarySheet->getStyle('A3:B3')->getFont()->setBold(true);

    // Segítséget szeretne - igen/nem statisztika
    $wantHelpCount = $surveys->where('want_help', 'Igen')->count();
    $summarySheet->setCellValue('A4', 'Segítséget szeretne:');
    $summarySheet->setCellValue('B4', $wantHelpCount . ' intézmény');
    $summarySheet->setCellValue('C4', '(' . round(($wantHelpCount / $surveys->count()) * 100, 1) . '%)');

    // Rendezvény szoftverek összesítése
    $summarySheet->setCellValue('A6', 'HASZNÁLT RENDEZVÉNYKEZELŐ SZOFTVEREK:');
    $summarySheet->getStyle('A6')->getFont()->setBold(true);
    $summarySheet->mergeCells('A6:C6');

    // Szoftverek - teljes érték alapján (nem vesszővel elválasztva)
    $softwareStats = [];
    foreach ($surveys as $survey) {
        $software = trim($survey->event_software);
        if (!empty($software) && $software != 'N/A' && $software != '-') {
            if (!isset($softwareStats[$software])) {
                $softwareStats[$software] = 0;
            }
            $softwareStats[$software]++;
        }
    }

    // ABC sorrendbe rendezés
    ksort($softwareStats);

    $row = 7;
    foreach ($softwareStats as $software => $count) {
        $summarySheet->setCellValue('A' . $row, $software);
        $summarySheet->setCellValue('B' . $row, $count . ' intézmény');
        $summarySheet->setCellValue('C' . $row, '(' . round(($count / $surveys->count()) * 100, 1) . '%)');
        $row++;
    }

    // Statisztikai problémák összesítése - teljes értékekkel, nem feldarabolva
    $row += 2;
    $summarySheet->setCellValue('A' . $row, 'STATISZTIKAI PROBLÉMÁK:');
    $summarySheet->getStyle('A' . $row)->getFont()->setBold(true);
    $summarySheet->mergeCells('A' . $row . ':C' . $row);
    $row++;

    $statIssuesStats = [];
    foreach ($surveys as $survey) {
        $issue = trim($survey->statistics_issues);
        if (!empty($issue) && $issue != 'N/A' && $issue != '-') {
            if (!isset($statIssuesStats[$issue])) {
                $statIssuesStats[$issue] = 0;
            }
            $statIssuesStats[$issue]++;
        }
    }

    ksort($statIssuesStats);

    foreach ($statIssuesStats as $issue => $count) {
        $summarySheet->setCellValue('A' . $row, $issue);
        $summarySheet->setCellValue('B' . $row, $count . ' intézmény');
        $summarySheet->setCellValue('C' . $row, '(' . round(($count / $surveys->count()) * 100, 1) . '%)');
        $row++;
    }

    // Kommunikációs problémák összesítése - teljes értékekkel
    $row += 2;
    $summarySheet->setCellValue('A' . $row, 'KOMMUNIKÁCIÓS PROBLÉMÁK:');
    $summarySheet->getStyle('A' . $row)->getFont()->setBold(true);
    $summarySheet->mergeCells('A' . $row . ':C' . $row);
    $row++;

    $commIssuesStats = [];
    foreach ($surveys as $survey) {
        $issue = trim($survey->communication_issues);
        if (!empty($issue) && $issue != 'N/A' && $issue != '-') {
            if (!isset($commIssuesStats[$issue])) {
                $commIssuesStats[$issue] = 0;
            }
            $commIssuesStats[$issue]++;
        }
    }

    ksort($commIssuesStats);

    foreach ($commIssuesStats as $issue => $count) {
        $summarySheet->setCellValue('A' . $row, $issue);
        $summarySheet->setCellValue('B' . $row, $count . ' intézmény');
        $summarySheet->setCellValue('C' . $row, '(' . round(($count / $surveys->count()) * 100, 1) . '%)');
        $row++;
    }

    // Információáramlás problémák összesítése - teljes értékekkel
    $row += 2;
    $summarySheet->setCellValue('A' . $row, 'INFORMÁCIÓÁRAMLÁS PROBLÉMÁK:');
    $summarySheet->getStyle('A' . $row)->getFont()->setBold(true);
    $summarySheet->mergeCells('A' . $row . ':C' . $row);
    $row++;

    $infoFlowStats = [];
    foreach ($surveys as $survey) {
        $issue = trim($survey->info_flow_issues);
        if (!empty($issue) && $issue != 'N/A' && $issue != '-') {
            if (!isset($infoFlowStats[$issue])) {
                $infoFlowStats[$issue] = 0;
            }
            $infoFlowStats[$issue]++;
        }
    }

    ksort($infoFlowStats);

    foreach ($infoFlowStats as $issue => $count) {
        $summarySheet->setCellValue('A' . $row, $issue);
        $summarySheet->setCellValue('B' . $row, $count . ' intézmény');
        $summarySheet->setCellValue('C' . $row, '(' . round(($count / $surveys->count()) * 100, 1) . '%)');
        $row++;
    }

    // Rendezvény követés előnyei - teljes értékekkel
    $row += 2;
    $summarySheet->setCellValue('A' . $row, 'RENDEZVÉNY KÖVETÉS ELŐNYEI:');
    $summarySheet->getStyle('A' . $row)->getFont()->setBold(true);
    $summarySheet->mergeCells('A' . $row . ':C' . $row);
    $row++;

    $trackingBenefitsStats = [];
    foreach ($surveys as $survey) {
        $benefit = trim($survey->event_tracking_benefits);
        if (!empty($benefit) && $benefit != 'N/A' && $benefit != '-') {
            if (!isset($trackingBenefitsStats[$benefit])) {
                $trackingBenefitsStats[$benefit] = 0;
            }
            $trackingBenefitsStats[$benefit]++;
        }
    }

    ksort($trackingBenefitsStats);

    foreach ($trackingBenefitsStats as $benefit => $count) {
        $summarySheet->setCellValue('A' . $row, $benefit);
        $summarySheet->setCellValue('B' . $row, $count . ' intézmény');
        $summarySheet->setCellValue('C' . $row, '(' . round(($count / $surveys->count()) * 100, 1) . '%)');
        $row++;
    }

    // Statisztikai előnyök - teljes értékekkel
    $row += 2;
    $summarySheet->setCellValue('A' . $row, 'STATISZTIKAI ELŐNYÖK:');
    $summarySheet->getStyle('A' . $row)->getFont()->setBold(true);
    $summarySheet->mergeCells('A' . $row . ':C' . $row);
    $row++;

    $statBenefitsStats = [];
    foreach ($surveys as $survey) {
        $benefit = trim($survey->stats_benefits);
        if (!empty($benefit) && $benefit != 'N/A' && $benefit != '-') {
            if (!isset($statBenefitsStats[$benefit])) {
                $statBenefitsStats[$benefit] = 0;
            }
            $statBenefitsStats[$benefit]++;
        }
    }

    ksort($statBenefitsStats);

    foreach ($statBenefitsStats as $benefit => $count) {
        $summarySheet->setCellValue('A' . $row, $benefit);
        $summarySheet->setCellValue('B' . $row, $count . ' intézmény');
        $summarySheet->setCellValue('C' . $row, '(' . round(($count / $surveys->count()) * 100, 1) . '%)');
        $row++;
    }

    // Összesítő munkalap formázása
    $summarySheet->getColumnDimension('A')->setAutoSize(true);
    $summarySheet->getColumnDimension('B')->setAutoSize(true);
    $summarySheet->getColumnDimension('C')->setAutoSize(true);

    // Aktív lap beállítása az Összesítő-re
    $spreadsheet->setActiveSheetIndex(1);

    // Excel fájl létrehozása
    $writer = new Xlsx($spreadsheet);
    $tempFile = tempnam(sys_get_temp_dir(), 'survey_export_');
    $writer->save($tempFile);

    return response()->download($tempFile, $fileName, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ])->deleteFileAfterSend(true);
}




    /**
     * Statistics - Kérdőív statisztikák
     */
    public function statistics()
{
    if (!Auth::user()->user_group === 'admin') {
        abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
    }

    // Összes kitöltött kérdőív
    $completedSurveysCount = Survey::count();

    // Folyamatban lévő kitöltések
    $inProgressSurveysCount = TemporarySurvey::where('is_completed', false)->count();

    // Intézmények száma
    $institutionsCount = CulturalInstitution::count();

    // Intézmények, amelyek már kitöltötték a kérdőívet
    $completedInstitutionsCount = CulturalInstitution::where('survey_completed', true)->count();

    // Havi statisztikák az elmúlt 12 hónapra
    $startDate = Carbon::now()->subMonths(12);
    $endDate = Carbon::now();

    $monthlySurveys = Survey::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as count')
        )
        ->where('created_at', '>=', $startDate)
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

    // Adatok előkészítése a grafikonhoz
    $chartLabels = [];
    $chartData = [];

    // Létrehozzuk a hónapokat az utolsó 12 hónapra
    $currentDate = Carbon::now()->startOfMonth()->subMonths(11);

    for ($i = 0; $i < 12; $i++) {
        $yearMonth = $currentDate->format('Y-m');
        $monthName = $currentDate->translatedFormat('Y. F');

        $chartLabels[] = $monthName;

        // Keressük meg az adott hónap adatait
        $monthData = $monthlySurveys->first(function ($item) use ($currentDate) {
            return $item->year == $currentDate->year && $item->month == $currentDate->month;
        });

        $chartData[] = $monthData ? $monthData->count : 0;

        $currentDate->addMonth();
    }

    // Segítségkérések száma
    $wantHelpCount = Survey::where('want_help', 'igen')->count();
    $probablyHelpCount = Survey::where('want_help', 'bizonytalan')->count();
    $wantHelpPercentage = $completedSurveysCount > 0 ? round(($wantHelpCount / $completedSurveysCount) * 100, 1) : 0;

    // Rendezvény szoftverek statisztikája
    $eventSoftwareStats = [];
    $eventSoftwares = Survey::whereNotNull('event_software')
        ->where('event_software', '!=', 'N/A')
        ->where('event_software', '!=', '-')
        ->select('event_software', DB::raw('count(*) as count'))
        ->groupBy('event_software')
        ->orderBy('count', 'desc')
        ->take(10)
        ->get();

    foreach ($eventSoftwares as $software) {
        $eventSoftwareStats[$software->event_software] = $software->count;
    }

    // Top 5 információáramlás probléma
    $infoFlowIssuesStats = [];
    if (Schema::hasColumn('surveys', 'info_flow_issues')) {
        $infoFlowIssues = Survey::whereNotNull('info_flow_issues')
            ->select('info_flow_issues', DB::raw('count(*) as count'))
            ->groupBy('info_flow_issues')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        foreach ($infoFlowIssues as $issue) {
            $infoFlowIssuesStats[$issue->info_flow_issues] = $issue->count;
        }
    }

    // Összegyűjtjük a szükséges adatokat a nézetben
    $stats = [
        'completedSurveysCount' => $completedSurveysCount,
        'inProgressSurveysCount' => $inProgressSurveysCount,
        'institutionsCount' => $institutionsCount,
        'completedInstitutionsCount' => $completedInstitutionsCount,
        'completionPercentage' => $institutionsCount > 0 ? round(($completedInstitutionsCount / $institutionsCount) * 100, 1) : 0,
        'wantHelpCount' => $wantHelpCount,
        'probalyHelpCount' => $probablyHelpCount,
        'wantHelpPercentage' => $wantHelpPercentage,
        'chartLabels' => $chartLabels,
        'chartData' => $chartData,
        'eventSoftwareStats' => $eventSoftwareStats,
        'infoFlowIssuesStats' => $infoFlowIssuesStats,
    ];

    return view('admin.statistics', compact('stats'));
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












/**
     * Eredménylevelek küldése oldal
     */
    public function resultLetters()
    {
        if (Auth::user()->user_group !== 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        // Lekérdezzük a surveys-okat email címekkel
        $surveysWithEmail = Survey::whereNotNull('contact')
            ->where('contact', '!=', '')
            ->where('contact', 'LIKE', '%@%')
            ->orderBy('created_at', 'desc')
            ->get();

        // Szűrjük az érvényes email címeket és frissítsük a statisztikákat
        $validSurveys = $surveysWithEmail->filter(function ($survey) {
            return filter_var($survey->contact, FILTER_VALIDATE_EMAIL);
        });

        // Statisztikák
        $totalSurveys = Survey::count();
        $surveysWithValidEmail = $validSurveys->count();
        $surveysWithoutEmail = $totalSurveys - $surveysWithValidEmail;
        $lettersSent = Survey::where('result_letter_sent', true)->count();
        $lettersNotSent = $surveysWithValidEmail - $lettersSent;

        return view('admin.result-letters.index', compact(
            'validSurveys',
            'totalSurveys',
            'surveysWithValidEmail',
            'surveysWithoutEmail',
            'lettersSent',
            'lettersNotSent'
        ));
    }

    /**
     * Eredménylevél küldése egy intézménynek
     */
    public function sendResultLetter($id)
    {
        if (Auth::user()->user_group !== 'admin') {
            abort(403, 'Nincs jogosultsága ehhez a művelethez.');
        }

        try {
            $survey = Survey::findOrFail($id);

            // Ellenőrizzük az email címet
            if (empty($survey->contact) || !filter_var($survey->contact, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Érvénytelen email cím: ' . $survey->contact
                ], 400);
            }

            // Ellenőrizzük, hogy már elküldtük-e
            if ($survey->result_letter_sent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Az eredménylevél már el lett küldve erre a címre: ' . $survey->contact
                ], 400);
            }

            // Email küldése közvetlenül
            \Illuminate\Support\Facades\Mail::to($survey->contact)->send(new \App\Mail\SurveyResultMail($survey));

            // Jelöljük meg, hogy elküldtük
            $survey->update([
                'result_letter_sent' => true,
                'result_letter_sent_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Az eredménylevél sikeresen elküldve: ' . $survey->institution_name
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Eredménylevél küldési hiba: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt az email küldése során: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Összes eredménylevél küldése
     */
    public function sendAllResultLetters()
    {
        if (Auth::user()->user_group !== 'admin') {
            abort(403, 'Nincs jogosultsága ehhez a művelethez.');
        }

        try {
            // Lekérdezzük az érvényes email címekkel rendelkező surveys-okat, amelyeknek még nem küldtük el
            $surveysToSend = Survey::whereNotNull('contact')
                ->where('contact', '!=', '')
                ->where('contact', 'LIKE', '%@%')
                ->where('result_letter_sent', false)
                ->get();

            $validSurveys = $surveysToSend->filter(function ($survey) {
                return filter_var($survey->contact, FILTER_VALIDATE_EMAIL);
            });

            $sentCount = 0;
            $errors = [];

            foreach ($validSurveys as $survey) {
                try {
                    \Illuminate\Support\Facades\Mail::to($survey->contact)->send(new \App\Mail\SurveyResultMail($survey));

                    $survey->update([
                        'result_letter_sent' => true,
                        'result_letter_sent_at' => now()
                    ]);

                    $sentCount++;
                } catch (\Exception $e) {
                    $errors[] = $survey->institution_name . ': ' . $e->getMessage();
                    \Illuminate\Support\Facades\Log::error('Eredménylevél küldési hiba (' . $survey->institution_name . '): ' . $e->getMessage());
                }
            }

            $message = "Eredménylevelek sikeresen elküldve: {$sentCount} db levél";
            if (!empty($errors)) {
                $message .= "\nHibák: " . implode(', ', $errors);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'sent_count' => $sentCount,
                'error_count' => count($errors)
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Tömeges eredménylevél küldési hiba: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a tömeges küldés során: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Előnézet az eredménylevélről
     */
    public function previewResultLetter($id)
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $survey = Survey::findOrFail($id);

        return view('emails.survey-result', [
            'institutionName' => $survey->institution_name,
            'survey' => $survey,
            'preview' => true
        ]);
    }



}
