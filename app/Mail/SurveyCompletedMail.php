<?php

namespace App\Mail;

use App\Models\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SurveyCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $survey;

    /**
     * Create a new message instance.
     *
     * @param Survey $survey
     * @return void
     */
    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Az intézmény neve mellett a radiogroup választások informativitásának növelése
        $infoFlowText = $this->getInfoFlowText();
        $subject = 'Új kitöltött kérdőív: ' . $this->survey->institution_name;

        if (!empty($infoFlowText)) {
            $subject .= ' - ' . $infoFlowText;
        }

        return $this->subject($subject)
                    ->view('emails.survey-completed');
    }

    /**
     * Az információáramlás választást ember által olvasható szöveggé alakítja
     *
     * @return string
     */
    private function getInfoFlowText()
    {
        switch ($this->survey->info_flow_issues) {
            case 'telephelyek':
                return 'Eltérő telephelyek';
            case 'munkaidő':
                return 'Eltérő munkaidő';
            case 'félreértések':
                return 'Félreértések';
            case 'online':
                return 'Online munkavégzés';
            case 'other':
                return 'Egyéb info. probléma';
            default:
                return '';
        }
    }
}
