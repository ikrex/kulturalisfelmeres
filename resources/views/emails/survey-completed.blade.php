<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Új kitöltött kérdőív</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
        }
        h1 {
            color: #4F5D75;
            border-bottom: 2px solid #BF4B75;
            padding-bottom: 10px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #4F5D75;
        }
        .box {
            background: #f5f5f5;
            padding: 15px;
            border-left: 4px solid #BF4B75;
            margin-bottom: 20px;
        }
        .section-title {
            color: #BF4B75;
            margin-top: 25px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Új kitöltött kérdőív érkezett</h1>

        <div class="box">
            <p>A következő intézmény kitöltötte a művelődési intézményi kérdőívet:</p>
            <h2>{{ $survey->institution_name }}</h2>
        </div>

        <div class="field">
            <div class="label">Kitöltés időpontja:</div>
            <div>{{ $survey->created_at->format('Y-m-d H:i:s') }}</div>
        </div>

        <div class="field">
            <div class="label">IP cím:</div>
            <div>{{ $survey->ip_address }}</div>
        </div>

        <div class="field">
            <div class="label">Használt rendezvényszervező szoftver(ek):</div>
            <div>{{ $survey->event_software ?: 'Nincs megadva' }}</div>
        </div>

        <!-- Új mezők: Információáramlási problémák -->
        <h3 class="section-title">Információáramlási problémák</h3>
        <div class="field">
            <div class="label">Választott opció:</div>
            <div>
                @switch($survey->info_flow_issues)
                    @case('telephelyek')
                        Eltérő telephelyeken dolgoznak a kollégák
                        @break
                    @case('munkaidő')
                        A kollégák munkaideje nem fedi egymást, nem találkoznak személyesen
                        @break
                    @case('félreértések')
                        Félreértések a szóbeli kommunikáció során
                        @break
                    @case('online')
                        A program segíti az online munkavégzést (kismamák, egyéb családi problémák esetén, családbarát munkahely kialakítás)
                        @break
                    @case('other')
                        Egyéb: {{ $survey->info_flow_issues_other_text }}
                        @break
                    @default
                        Nincs megadva
                @endswitch
            </div>
        </div>

        <!-- Új mezők: Visszakereshető rendezvény előnyei -->
        <h3 class="section-title">Visszakereshető rendezvény előnyei</h3>
        <div class="field">
            <div class="label">Választott opció:</div>
            <div>
                @switch($survey->event_tracking_benefits)
                    @case('partner_változás')
                        Rendezvény évek óta ugyanaz, de pl. a partner neve (szerződő fél) változik
                        @break
                    @case('munkakör_átadás')
                        Kilépés esetén egyszerűsíti a munkakör átadását
                        @break
                    @case('betegség')
                        Betegség esetén sincs fennakadás, hiszen látjuk, hol tart a rendezvény szervezése
                        @break
                    @case('other')
                        Egyéb: {{ $survey->event_tracking_benefits_other_text }}
                        @break
                    @default
                        Nincs megadva
                @endswitch
            </div>
        </div>

        <!-- Új mezők: Statisztika, kimutatás, beszámoló előnyei -->
        <h3 class="section-title">Statisztika, kimutatás, BESZÁMOLÓ előnyei</h3>
        <div class="field">
            <div class="label">Választott opció:</div>
            <div>
                @switch($survey->stats_benefits)
                    @case('friss_vélemény')
                        Év közben frissen írjuk a véleményt
                        @break
                    @case('több_kolléga')
                        Több kolléga beszámolója rögzíthető
                        @break
                    @case('ksh_szűrés')
                        Év végén már csak szűrni kell a KSH adatokat
                        @break
                    @case('nincs_tévedés')
                        Szinte nulla a tévedés esélye
                        @break
                    @case('other')
                        Egyéb: {{ $survey->stats_benefits_other_text }}
                        @break
                    @default
                        Nincs megadva
                @endswitch
            </div>
        </div>

        <h3 class="section-title">További információk</h3>
        <div class="field">
            <div class="label">Milyen nehézségeket okoz a statisztikák, kimutatások készítése?</div>
            <div>{{ $survey->statistics_issues ?: 'Nincs megadva' }}</div>
        </div>

        <div class="field">
            <div class="label">Milyen kihívások vannak az információáramlásban és szervezésben?</div>
            <div>{{ $survey->communication_issues ?: 'Nincs megadva' }}</div>
        </div>

        <div class="field">
            <div class="label">Átláthatók-e az események és azok dokumentálása?</div>
            <div>{{ $survey->event_transparency ?: 'Nincs megadva' }}</div>
        </div>

        <div class="field">
            <div class="label">Nyitott lenne ingyenes tanácsadásra vagy módszertani segítségre?</div>
            <div>{{ ucfirst($survey->want_help) }}</div>
        </div>

        <div class="field">
            <div class="label">Elérhetőség:</div>
            <div>{{ $survey->contact ?: 'Nincs megadva' }}</div>
        </div>

        <p>A kérdőív adatai megtekinthetők az admin felületen is.</p>
    </div>
</body>
</html>
