<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * A kapcsolati űrlap adatai.
     *
     * @var array
     */
    public $data;

    /**
     * Új példány létrehozása.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Az üzenet felépítése.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Új kapcsolati űrlap: ' . $this->data['subject'])
                    ->view('emails.contact-form')
                    ->with([
                        'name' => $this->data['name'],
                        'email' => $this->data['email'],
                        'subject' => $this->data['subject'],
                        'message' => $this->data['message'],
                    ]);
    }
}
