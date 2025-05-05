<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CulturalInstitution;
use App\Http\Services\ExcelImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstitutionsController extends Controller
{



    /**
 * Display a listing of institutions.
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\View\View
 */
public function index(Request $request)
{
    if (!Auth::user()->user_group === 'admin') {
        abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
    }

    $query = CulturalInstitution::query();

    // Szűrés név alapján - kis-nagybetű érzéketlen keresés
    if ($request->has('name') && !empty($request->name)) {
        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->name) . '%']);
    }

    // Szűrés kitöltési állapot alapján
    if ($request->has('survey_completed') && $request->survey_completed !== '') {
        $query->where('survey_completed', $request->survey_completed);
    }

    // Szűrés régió alapján - kis-nagybetű érzéketlen keresés
    if ($request->has('region') && !empty($request->region)) {
        $query->whereRaw('LOWER(region) LIKE ?', ['%' . strtolower($request->region) . '%']);
    }

    // Rendezés
    $sortField = $request->get('sort', 'name');
    $sortDirection = $request->get('direction', 'asc');
    $query->orderBy($sortField, $sortDirection);


    $institutions = $query->paginate(15);


    // Régiók lista a szűrőhöz
    $regions = CulturalInstitution::select('region')
        ->whereNotNull('region')
        ->distinct()
        ->orderBy('region')
        ->pluck('region');

    return view('admin.institutions.index', compact('institutions', 'regions'));
}



    /**
     * Show the form for uploading institutions Excel.
     *
     * @return \Illuminate\View\View
     */
    public function uploadForm()
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        return view('admin.institutions.upload');
    }

    /**
     * Import institutions from Excel.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\ExcelImportService $importService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request, ExcelImportService $importService)
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $result = $importService->importCulturalInstitutions($request->file('excel_file'));

        if ($result['success']) {
            return redirect()->route('admin.institutions.index')
                ->with('success', 'Sikeres importálás! Importált: ' . $result['imported'] .
                    ', Frissített: ' . $result['updated'] .
                    ', Hibás: ' . $result['errors'] .
                    ' (Összesen: ' . $result['total'] . ')');
        } else {
            return redirect()->back()->with('error', 'Hiba történt az importálás során: ' . $result['message']);
        }
    }

    /**
     * Show the form for editing the specified institution.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $institution = CulturalInstitution::findOrFail($id);

        return view('admin.institutions.edit', compact('institution'));
    }

    /**
     * Update the specified institution in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'region' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255|url',
            'survey_completed' => 'boolean',
        ]);

        $institution = CulturalInstitution::findOrFail($id);
        $institution->update($request->all());

        return redirect()->route('admin.institutions.index')
            ->with('success', 'Intézmény sikeresen frissítve!');
    }

    /**
     * Remove the specified institution from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $institution = CulturalInstitution::findOrFail($id);
        $institution->delete();

        return redirect()->route('admin.institutions.index')
            ->with('success', 'Intézmény sikeresen törölve!');
    }

    /**
     * Generate tracking links for all institutions without tracking codes.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateTrackingCodes()
    {
        if (!Auth::user()->user_group === 'admin') {
            abort(403, 'Nincs jogosultsága az oldal megtekintéséhez.');
        }

        $institutions = CulturalInstitution::whereNull('tracking_code')->get();

        foreach ($institutions as $institution) {
            $institution->tracking_code = CulturalInstitution::generateTrackingCode();
            $institution->save();
        }

        return redirect()->route('admin.institutions.index')
            ->with('success', 'Követőkódok sikeresen generálva! (' . $institutions->count() . ' intézmény)');
    }
}
