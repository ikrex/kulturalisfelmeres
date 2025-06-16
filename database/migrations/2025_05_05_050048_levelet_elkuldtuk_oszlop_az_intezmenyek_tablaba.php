<?php

namespace Database\Migrations;

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
        Schema::table('cultural_institutions', function (Blueprint $table) {
            $table->boolean('email_sent')->default(false)->after('survey_completed');
            $table->timestamp('last_email_sent_at')->nullable()->after('email_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cultural_institutions', function (Blueprint $table) {
            $table->dropColumn(['email_sent', 'last_email_sent_at']);
        });
    }
};
