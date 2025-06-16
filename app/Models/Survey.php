<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    /**
     * A tömegesen kitölthető attribútumok.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'institution_name',
        'event_software',
        'statistics_issues',
        'communication_issues',
        'event_transparency',
        'want_help',
        'contact',
        'ip_address',
        // Új mezők a radiogroup-ok számára
        'tracking_code',
        'info_flow_issues',
        'info_flow_issues_other_text',
        'event_tracking_benefits',
        'event_tracking_benefits_other_text',
        'stats_benefits',
        'stats_benefits_other_text',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Az ideiglenes kérdőív lekérdezése.
     */
    public function temporarySurvey()
    {
        return $this->belongsTo(TemporarySurvey::class, 'uuid', 'uuid');
    }

/**
     * Ellenőrzi, hogy van-e érvényes email cím
     */
    public function hasValidEmail()
    {
        return !empty($this->contact) && filter_var($this->contact, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Megadja a segítségkérés státuszát emberi formában
     */
    public function getWantHelpDisplayAttribute()
    {
        switch ($this->want_help) {
            case 'igen':
                return 'Igen';
            case 'bizonytalan':
                return 'Bizonytalan';
            default:
                return 'Nem';
        }
    }

    /**
     * Scope az érvényes email címmel rendelkező surveys-okhoz
     */
    public function scopeWithValidEmail($query)
    {
        return $query->whereNotNull('contact')
                    ->where('contact', '!=', '')
                    ->where('contact', 'LIKE', '%@%');
    }

}
