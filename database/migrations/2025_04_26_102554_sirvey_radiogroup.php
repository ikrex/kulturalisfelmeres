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
            // Információáramlás problémák
            $table->string('info_flow_issues')->nullable();
            $table->text('info_flow_issues_other_text')->nullable();

            // Visszakereshető rendezvény előnyei
            $table->string('event_tracking_benefits')->nullable();
            $table->text('event_tracking_benefits_other_text')->nullable();

            // Statisztika, kimutatás előnyei
            $table->string('stats_benefits')->nullable();
            $table->text('stats_benefits_other_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn([
                'info_flow_issues',
                'info_flow_issues_other_text',
                'event_tracking_benefits',
                'event_tracking_benefits_other_text',
                'stats_benefits',
                'stats_benefits_other_text'
            ]);
        });
    }
};
