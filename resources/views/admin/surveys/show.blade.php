@extends('layouts.admin')

@section('title', 'Kérdőív részletei')

@section('content')
<div class="container mx-auto max-w-5xl px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kérdőív részletei</h1>
        <a href="{{ route('admin.surveys.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded transition">
            Vissza a listához
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b">
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold text-gray-800">{{ $survey->institution_name }}</h2>
                <span class="text-sm text-gray-500">Kitöltve: {{ $survey->created_at->format('Y.m.d. H:i') }}</span>
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <span class="mr-4">UUID: {{ $survey->uuid }}</span>
                <span>IP: {{ $survey->ip_address }}</span>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1 md:col-span-2">
                <h3 class="font-semibold text-gray-700 mb-2">Intézmény neve</h3>
                <p class="bg-gray-100 p-3 rounded">{{ $survey->institution_name }}</p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Használt rendezvényszervező szoftver(ek)</h3>
                <p class="bg-gray-100 p-3 rounded min-h-[60px]">{{ $survey->event_software ?: 'Nincs megadva' }}</p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Nyitott tanácsadásra?</h3>
                <p class="bg-gray-100 p-3 rounded min-h-[60px]">{{ ucfirst($survey->want_help) }}</p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Elérhetőség</h3>
                <p class="bg-gray-100 p-3 rounded min-h-[60px]">{{ $survey->contact ?: 'Nincs megadva' }}</p>
            </div>

            <!-- Új mezők: Információáramlás -->
            <div class="col-span-1 md:col-span-2">
                <h3 class="font-semibold text-gray-700 mb-2">Információáramlási problémák</h3>
                @if($survey->info_flow_issues)
                    <div class="bg-gray-100 p-3 rounded min-h-[60px]">
                        @switch($survey->info_flow_issues)
                            @case('telephelyek')
                                <p>Eltérő telephelyeken dolgoznak a kollégák</p>
                                @break
                            @case('munkaidő')
                                <p>A kollégák munkaideje nem fedi egymást, nem találkoznak személyesen</p>
                                @break
                            @case('félreértések')
                                <p>Félreértések a szóbeli kommunikáció során</p>
                                @break
                            @case('online')
                                <p>A program segíti az online munkavégzést (kismamák, egyéb családi problémák esetén, családbarát munkahely kialakítás)</p>
                                @break
                            @case('other')
                                <p><strong>Egyéb:</strong> {{ $survey->info_flow_issues_other_text }}</p>
                                @break
                            @default
                                <p>{{ $survey->info_flow_issues }}</p>
                        @endswitch
                    </div>
                @else
                    <p class="bg-gray-100 p-3 rounded min-h-[60px]">Nincs megadva</p>
                @endif
            </div>

            <!-- Új mezők: Rendezvénykövetés előnyei -->
            <div class="col-span-1 md:col-span-2">
                <h3 class="font-semibold text-gray-700 mb-2">Visszakereshető rendezvény előnyei</h3>
                @if($survey->event_tracking_benefits)
                    <div class="bg-gray-100 p-3 rounded min-h-[60px]">
                        @switch($survey->event_tracking_benefits)
                            @case('partner_változás')
                                <p>Rendezvény évek óta ugyanaz, de pl. a partner neve (szerződő fél) változik</p>
                                @break
                            @case('munkakör_átadás')
                                <p>Kilépés esetén egyszerűsíti a munkakör átadását</p>
                                @break
                            @case('betegség')
                                <p>Betegség esetén sincs fennakadás, hiszen látjuk, hol tart a rendezvény szervezése</p>
                                @break
                            @case('other')
                                <p><strong>Egyéb:</strong> {{ $survey->event_tracking_benefits_other_text }}</p>
                                @break
                            @default
                                <p>{{ $survey->event_tracking_benefits }}</p>
                        @endswitch
                    </div>
                @else
                    <p class="bg-gray-100 p-3 rounded min-h-[60px]">Nincs megadva</p>
                @endif
            </div>

            <!-- Új mezők: Statisztika, kimutatás előnyei -->
            <div class="col-span-1 md:col-span-2">
                <h3 class="font-semibold text-gray-700 mb-2">Statisztika, kimutatás, BESZÁMOLÓ előnyei</h3>
                @if($survey->stats_benefits)
                    <div class="bg-gray-100 p-3 rounded min-h-[60px]">
                        @switch($survey->stats_benefits)
                            @case('friss_vélemény')
                                <p>Év közben frissen írjuk a véleményt</p>
                                @break
                            @case('több_kolléga')
                                <p>Több kolléga beszámolója rögzíthető</p>
                                @break
                            @case('ksh_szűrés')
                                <p>Év végén már csak szűrni kell a KSH adatokat</p>
                                @break
                            @case('nincs_tévedés')
                                <p>Szinte nulla a tévedés esélye</p>
                                @break
                            @case('other')
                                <p><strong>Egyéb:</strong> {{ $survey->stats_benefits_other_text }}</p>
                                @break
                            @default
                                <p>{{ $survey->stats_benefits }}</p>
                        @endswitch
                    </div>
                @else
                    <p class="bg-gray-100 p-3 rounded min-h-[60px]">Nincs megadva</p>
                @endif
            </div>

            <div class="col-span-1 md:col-span-2">
                <h3 class="font-semibold text-gray-700 mb-2">Statisztikák, kimutatások nehézségei</h3>
                <p class="bg-gray-100 p-3 rounded min-h-[60px]">{{ $survey->statistics_issues ?: 'Nincs megadva' }}</p>
            </div>

            <div class="col-span-1 md:col-span-2">
                <h3 class="font-semibold text-gray-700 mb-2">Információáramlási és szervezési kihívások</h3>
                <p class="bg-gray-100 p-3 rounded min-h-[60px]">{{ $survey->communication_issues ?: 'Nincs megadva' }}</p>
            </div>

            <div class="col-span-1 md:col-span-2">
                <h3 class="font-semibold text-gray-700 mb-2">Események átláthatósága és dokumentálása</h3>
                <p class="bg-gray-100 p-3 rounded min-h-[60px]">{{ $survey->event_transparency ?: 'Nincs megadva' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
