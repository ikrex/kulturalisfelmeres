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
        // Ellenőrizzük, hogy a tracking_code oszlop létezik-e már a táblában
        if (!Schema::hasColumn('temporary_surveys', 'tracking_code')) {
            Schema::table('temporary_surveys', function (Blueprint $table) {
                $table->string('tracking_code')->nullable()->after('is_completed');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporary_surveys', function (Blueprint $table) {
            $table->dropColumn('tracking_code');
        });
    }
};
