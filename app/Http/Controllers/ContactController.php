<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Megjeleníti a kapcsolat formot.
     *
     * @return \Illuminate\View\View
     */
    public function showContactForm()
    {
        return view('contact');
    }

    /**
     * Feldolgozza a kapcsolati űrlapot.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendContactForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        // Email küldése a kapcsolati űrlapról
        try {
            Mail::to('illeskalman77@gmail.com')->send(new ContactFormMail($validatedData));

            // Naplózás
            Log::info('Kapcsolati űrlap elküldve', $validatedData);

            return back()->with('success', 'Köszönjük! Üzenetét sikeresen elküldtük.');
        } catch (\Exception $e) {
            Log::error('Hiba történt az üzenet küldése közben: ' . $e->getMessage());
            return back()->with('error', 'Sajnos hiba történt az üzenet küldése közben. Kérjük próbálja újra később.');
        }

        // Valós környezetben itt küldenénk el az e-mailt
        // Mail::to('info@kulturaliskutatas.hu')->send(new ContactFormMail($validatedData));

        return back()->with('success', 'Köszönjük! Üzenetét sikeresen elküldtük.');
    }
}
