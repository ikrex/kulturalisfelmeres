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
        Schema::create('kulturalis_intezmenyek', function (Blueprint $table) {
            $table->id();
            $table->string('intezmeny_neve');
            $table->string('vezeto_neve');
            $table->string('vezeto_email')->nullable();
            $table->string('intezmeny_cime');
            $table->boolean('kuldheto_level')->default(true);
            $table->boolean('aktiv_kontakt')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kulturalis_intezmenyek');
    }
};
