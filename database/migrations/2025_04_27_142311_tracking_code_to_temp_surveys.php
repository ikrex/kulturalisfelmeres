<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Csak akkor adjuk hozzá a mezőt, ha még nem létezik
        if (!Schema::hasColumn('temporary_surveys', 'tracking_code')) {
            Schema::table('temporary_surveys', function (Blueprint $table) {
                $table->string('tracking_code')->nullable()->after('ip_address');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('temporary_surveys', 'tracking_code')) {
            Schema::table('temporary_surveys', function (Blueprint $table) {
                $table->dropColumn('tracking_code');
            });
        }
    }
};
