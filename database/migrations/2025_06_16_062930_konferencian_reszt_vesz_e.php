<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->boolean('conference_attendance')->default(false)->after('stats_benefits_other_text');
            $table->integer('conference_attendees')->nullable()->after('conference_attendance');
            $table->timestamp('conference_response_at')->nullable()->after('conference_attendees');
            $table->boolean('result_letter_sent')->default(false)->after('conference_response_at');
            $table->timestamp('result_letter_sent_at')->nullable()->after('result_letter_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn([
                'conference_attendance',
                'conference_attendees',
                'conference_response_at',
                'result_letter_sent',
                'result_letter_sent_at'
            ]);
        });
    }
};
