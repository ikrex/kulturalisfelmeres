<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        // Valós környezetben itt küldenénk el az e-mailt
        // Mail::to('info@kulturaliskutatas.hu')->send(new ContactFormMail($validatedData));

        return back()->with('success', 'Köszönjük! Üzenetét sikeresen elküldtük.');
    }
}
