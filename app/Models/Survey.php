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
    ];

    /**
     * Az ideiglenes kérdőív lekérdezése.
     */
    public function temporarySurvey()
    {
        return $this->belongsTo(TemporarySurvey::class, 'uuid', 'uuid');
    }
}
