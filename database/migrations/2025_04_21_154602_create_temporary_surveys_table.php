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
        Schema::create('temporary_surveys', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('institution_name')->nullable();
            $table->string('event_software')->nullable();
            $table->text('statistics_issues')->nullable();
            $table->text('communication_issues')->nullable();
            $table->text('event_transparency')->nullable();
            $table->string('want_help')->nullable();
            $table->string('contact')->nullable();
            $table->ipAddress('ip_address');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });

        // Módosítjuk a surveys táblát, hogy tartalmazzon uuid-t
        Schema::table('surveys', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
            $table->ipAddress('ip_address')->after('contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_surveys');

        // Eltávolítjuk az uuid és ip_address oszlopokat a surveys táblából
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->dropColumn('ip_address');
        });
    }
};
