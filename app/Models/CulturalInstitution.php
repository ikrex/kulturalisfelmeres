<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CulturalInstitution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'contact_person',
        'phone',
        'address',
        'city',
        'postal_code',
        'region',
        'website',
        'tracking_code',
        'survey_completed',
        'email_opens',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'survey_completed' => 'boolean',
        'email_opens' => 'array',
    ];

    /**
     * Generate a unique tracking code for the institution.
     *
     * @return string
     */
    public static function generateTrackingCode()
    {
        $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        // Ellenőrizzük, hogy egyedi-e a kód
        while (self::where('tracking_code', $code)->exists()) {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        }

        return $code;
    }

    /**
     * Log email open event.
     *
     * @return void
     */
    public function logEmailOpen()
    {
        $opens = $this->email_opens ?? [];
        $opens[] = now()->toDateTimeString();
        $this->email_opens = $opens;
        $this->save();
    }

    /**
     * Get the survey associated with this institution.
     */
    public function survey()
    {
        return $this->hasOne(Survey::class, 'institution_id');
    }
}
