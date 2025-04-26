<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporarySurvey extends Model
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
        'is_completed',
        // Új mezők a radiogroup-ok számára
        'info_flow_issues',
        'info_flow_issues_other_text',
        'event_tracking_benefits',
        'event_tracking_benefits_other_text',
        'stats_benefits',
        'stats_benefits_other_text',
    ];

    /**
     * A kapcsolódó végleges kérdőív lekérdezése (ha létezik).
     */
    public function completedSurvey()
    {
        return $this->hasOne(Survey::class, 'uuid', 'uuid');
    }
}
