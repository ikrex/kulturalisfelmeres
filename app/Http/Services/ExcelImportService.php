<?php

namespace App\Http\Services;

use App\Models\CulturalInstitution;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ExcelImportService
{
     /**
     * Import cultural institutions from Excel file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public function importCulturalInstitutions(UploadedFile $file)
    {
        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Az első sor a fejléc
            $header = array_shift($rows);

            $imported = 0;
            $errors = 0;
            $updated = 0;

            foreach ($rows as $row) {
                // Adatok kinyerése a sorból
                $institutionData = $this->mapRowToInstitutionData($header, $row);

                if (empty($institutionData['name']) || empty($institutionData['email'])) {
                    $errors++;
                    continue;
                }

                // Ellenőrizzük, hogy létezik-e már ez az intézmény
                $institution = CulturalInstitution::where('email', $institutionData['email'])->first();

                if ($institution) {
                    // Frissítjük a meglévő intézményt
                    $institution->update($institutionData);
                    $updated++;
                } else {
                    // Új intézmény létrehozása
                    $institutionData['tracking_code'] = CulturalInstitution::generateTrackingCode();
                    $institutionData['email_opens'] = [];
                    CulturalInstitution::create($institutionData);
                    $imported++;
                }
            }

            return [
                'success' => true,
                'imported' => $imported,
                'updated' => $updated,
                'errors' => $errors,
                'total' => count($rows),
            ];
        } catch (\Exception $e) {
            Log::error('Excel import error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Map Excel row to institution data.
     *
     * @param array $header
     * @param array $row
     * @return array
     */
    private function mapRowToInstitutionData($header, $row)
    {
        $data = [];

        // Alap mezők megfeleltetése
        $fieldMapping = [
            'Név' => 'name',
            'Intézmény neve' => 'name',
            'Kultúrház neve' => 'name',
            'E-mail' => 'email',
            'Email' => 'email',
            'Email cím' => 'email',
            'Kapcsolattartó' => 'contact_person',
            'Kapcsolattartó neve' => 'contact_person',
            'Telefon' => 'phone',
            'Telefonszám' => 'phone',
            'Cím' => 'address',
            'Város' => 'city',
            'Település' => 'city',
            'Irányítószám' => 'postal_code',
            'Régió' => 'region',
            'Megye' => 'region',
            'Weboldal' => 'website',
            'Honlap' => 'website',
        ];

        // Adatok hozzárendelése a modellhez
        foreach ($header as $columnIndex => $columnName) {
            if (isset($fieldMapping[$columnName]) && !empty($row[$columnIndex])) {
                $fieldName = $fieldMapping[$columnName];
                $data[$fieldName] = $row[$columnIndex];
            }
        }

        return $data;
    }
}
