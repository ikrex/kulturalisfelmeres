<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\TemporarySurvey;
use App\Models\Response;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



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





/**
 * Export surveys to Excel file
 *
 * @param \Illuminate\Database\Eloquent\Collection $surveys
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
 */
private function exportSurveysFaszsag($surveys)
{
    $fileName = 'surveys_' . date('Y-m-d') . '.xlsx';

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Fejlécek
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Kérdőív neve');
    $sheet->setCellValue('C1', 'Létrehozó');
    $sheet->setCellValue('D1', 'Létrehozva');
    $sheet->setCellValue('E1', 'Kitöltések');
    $sheet->setCellValue('F1', 'Státusz');

    // Adatok
    $row = 2;
    foreach ($surveys as $survey) {
        $responsesCount = Response::where('survey_id', $survey->id)->count();

        $sheet->setCellValue('A' . $row, $survey->id);
        $sheet->setCellValue('B' . $row, $survey->name);
        $sheet->setCellValue('C' . $row, $survey->user->name ?? 'N/A');
        $sheet->setCellValue('D' . $row, $survey->created_at->format('Y-m-d H:i'));
        $sheet->setCellValue('E' . $row, $responsesCount);
        $sheet->setCellValue('F' . $row, $survey->is_active ? 'Aktív' : 'Inaktív');

        $row++;
    }

    // Táblázat formázása
    foreach(range('A','F') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Excel fájl létrehozása
    $writer = new Xlsx($spreadsheet);
    $tempFile = tempnam(sys_get_temp_dir(), 'survey_export_');
    $writer->save($tempFile);

    return response()->download($tempFile, $fileName, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ])->deleteFileAfterSend(true);
}

}
