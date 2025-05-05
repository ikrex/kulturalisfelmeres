<?php

namespace App\Http\Controllers;

use App\Models\CulturalInstitution;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrackingController extends Controller
{
    /**
     * Track email open event.
     *
     * @param Request $request
     * @param string $code
     * @return Response
     */
    public function trackEmail(Request $request, $code)
    {
        // Keressük meg az intézményt a követőkód alapján
        $institution = CulturalInstitution::where('tracking_code', $code)->first();

        if ($institution) {
            // Rögzítjük a megnyitás időpontját
            $institution->logEmailOpen();
        }

        // 1x1 pixel transparent GIF képet adunk vissza
        $transparentPixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

        return response($transparentPixel)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    /**
     * Redirect to survey with tracking code.
     *
     * @param Request $request
     * @param string $code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToSurvey(Request $request, $code)
    {
        // Keressük meg az intézményt a követőkód alapján
        $institution = CulturalInstitution::where('tracking_code', $code)->first();

        if ($institution) {
            // Rögzítjük a kattintást
            $institution->logEmailOpen();

            // Átirányítunk a felmérésre a követőkóddal
            return redirect()->route('survey.form', ['code' => $code]);
        }

        // Ha nincs ilyen intézmény, átirányítunk a sima felmérésre
        return redirect()->route('survey.form');
    }
}
