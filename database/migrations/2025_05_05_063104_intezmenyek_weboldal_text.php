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
        Schema::table('cultural_institutions', function (Blueprint $table) {
            // Először módosítjuk a website oszlop típusát string-ről text-re
            $table->text('website')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cultural_institutions', function (Blueprint $table) {
            // Visszaállítjuk a website oszlop típusát text-ről string-re
            $table->string('website', 255)->nullable()->change();
        });
    }
};
