<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KulturalisIntezmenyekSeeder extends Seeder
{
    /**
     * Kulturális intézmények feltöltése a KulturhazskSzurtLista2023.xlsx fájl alapján.
     *
     * @return void
     */
    public function run(): void
    {
        // Tömbben tároljuk az adatokat
        $intezmenyek = [
            [
                'intezmeny_neve' => 'Ágasegyházi IKSZT és Sportolási Központ',
                'vezeto_neve' => 'Füredi János',
                'vezeto_email' => 'ikszt@agasegyhaza.hu', // Weboldalról vagy kereséssel kiegészítve
                'intezmeny_cime' => '6076 Ágasegyháza, Kossuth tér 2.',
                'kuldheto_level' => true,
                'aktiv_kontakt' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [
                'intezmeny_neve' => '',
                'vezeto_neve' => '',
                'vezeto_email' => '',
                'intezmeny_cime' => '',
                'kuldheto_level' => true,
                'aktiv_kontakt' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [
                'intezmeny_neve' => '',
                'vezeto_neve' => '',
                'vezeto_email' => '',
                'intezmeny_cime' => '',
                'kuldheto_level' => true,
                'aktiv_kontakt' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],






        ];

        // Az adatok beillesztése az adatbázisba
        DB::table('kulturalis_intezmenyek')->insert($intezmenyek);
    }
}
