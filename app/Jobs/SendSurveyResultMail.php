<?php

namespace App\Jobs;

use App\Models\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendSurveyResultMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $survey;

    /**
     * Create a new job instance.
     */
    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Ellenőrizzük, hogy van-e email cím
            if (empty($this->survey->contact) || !filter_var($this->survey->contact, FILTER_VALIDATE_EMAIL)) {
                Log::warning("Érvénytelen email cím a survey ID: {$this->survey->id} esetében: {$this->survey->contact}");
                return;
            }

            // Email küldése
            Mail::to($this->survey->contact)->send(new \App\Mail\SurveyResultMail($this->survey));

            Log::info("Eredménylevél sikeresen elküldve: {$this->survey->contact} (Survey ID: {$this->survey->id})");

        } catch (\Exception $e) {
            Log::error("Hiba az eredménylevél küldésekor (Survey ID: {$this->survey->id}): " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Az eredménylevél küldése sikertelen volt (Survey ID: {$this->survey->id}): " . $exception->getMessage());
    }
}
