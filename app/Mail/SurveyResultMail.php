<?php

namespace App\Mail;

use App\Models\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class SurveyResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $survey;

    /**
     * Create a new message instance.
     */
    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Köszönetlevél és következő lépések - Művelődési intézményi digitalizációs felmérés',
            from: env('MAIL_FROM_ADDRESS', 'info@kulturaliskutatas.hu'),
            replyTo: [
                'info@kulturaliskutatas.hu',
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.survey-result',
            with: [
                'institutionName' => $this->survey->institution_name,
                'survey' => $this->survey,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $attachments = [];

        // Tanulmány csatolása (PDF formátumban)
        $studyPath = storage_path('app/public/documents/Kulturális_kutatás_2025_Május_tanulmány.pdf');

        if (file_exists($studyPath)) {
            $attachments[] = Attachment::fromPath($studyPath)
                ->as('Művelődési_Intézmények_Digitalizációs_Felmérése_2025.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
