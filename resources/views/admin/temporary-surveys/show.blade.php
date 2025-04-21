@extends('layouts.admin')
@section('title', 'Ideiglenes kérdőív részletei')
@section('page-title', 'Ideiglenes kérdőív részletei')

@section('page-actions')
<a href="{{ route('admin.temporary-surveys.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
    </svg>
    Vissza a listához
</a>
@endsection

@section('content')
<div class="admin-card mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">
            Ideiglenes kérdőív információk
            @if($temporarySurvey->is_completed)
                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    Befejezett
                </span>
            @else
                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    Folyamatban
                </span>
            @endif
        </h2>
        <div class="bg-gray-100 rounded-lg px-4 py-2 text-sm text-gray-700">
            Elkezdve: {{ $temporarySurvey->created_at->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Azonosítók</h3>
            <div class="bg-gray-50 p-4 rounded mb-4">
                <p><span class="font-semibold">UUID:</span> {{ $temporarySurvey->uuid }}</p>
                <p><span class="font-semibold">IP Cím:</span> {{ $temporarySurvey->ip_address }}</p>
                <p><span class="font-semibold">Utoljára módosítva:</span> {{ $temporarySurvey->updated_at->format('Y-m-d H:i:s') }}</p>
            </div>

            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Kitöltöttség állapota</h3>
            <div class="bg-gray-50 p-4 rounded">
                <div class="mb-4">
                    <p class="font-semibold">Kitöltöttségi mutató:</p>
                    @php
                        $fields = [
                            'institution_name', 'event_software', 'statistics_issues',
                            'communication_issues', 'event_transparency', 'want_help',
                            'contact'
                        ];
                        $filledFields = 0;
                        foreach ($fields as $field) {
                            if (!empty($temporarySurvey->$field)) {
                                $filledFields++;
                            }
                        }
                        $percentage = round(($filledFields / count($fields)) * 100);
                    @endphp
                    <div class="mt-2 bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-right mt-1 text-sm text-gray-600">{{ $percentage }}% kitöltve</p>
                </div>

                @if($completedSurvey)
                <div>
                    <p class="font-semibold">Végleges beküldés:</p>
                    <p class="mt-1">Beküldve: {{ $completedSurvey->created_at->format('Y-m-d H:i:s') }}</p>
                    <p class="mt-1">
                        <a href="{{ route('admin.surveys.show', $completedSurvey->id) }}" class="text-indigo-600 hover:text-indigo-900">
                            Végleges kérdőív megtekintése
                        </a>
                    </p>
                </div>
                @endif
            </div>
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Kitöltött adatok</h3>
            <div class="bg-gray-50 p-4 rounded">
                <div class="mb-4">
                    <p class="font-semibold">Intézmény neve:</p>
                    <p class="mt-1">{{ $temporarySurvey->institution_name ?: 'Nincs megadva' }}</p>
                </div>

                <div class="mb-4">
                    <p class="font-semibold">Használt rendezvényszervező szoftver(ek):</p>
                    <p class="mt-1">{{ $temporarySurvey->event_software ?: 'Nincs megadva' }}</p>
                </div>

                <div class="mb-4">
                    <p class="font-semibold">Milyen nehézségeket okoz a statisztikák, kimutatások készítése?</p>
                    <p class="mt-1">{{ $temporarySurvey->statistics_issues ?: 'Nincs megadva' }}</p>
                </div>

                <div class="mb-4">
                    <p class="font-semibold">Milyen kihívások vannak az információáramlásban és szervezésben?</p>
                    <p class="mt-1">{{ $temporarySurvey->communication_issues ?: 'Nincs megadva' }}</p>
                </div>

                <div class="mb-4">
                    <p class="font-semibold">Átláthatók-e az események és azok dokumentálása?</p>
                    <p class="mt-1">{{ $temporarySurvey->event_transparency ?: 'Nincs megadva' }}</p>
                </div>

                <div class="mb-4">
                    <p class="font-semibold">Nyitott lenne ingyenes tanácsadásra vagy módszertani segítségre?</p>
                    <p class="mt-1">{{ $temporarySurvey->want_help ?: 'Nincs megadva' }}</p>
                </div>

                <div>
                    <p class="font-semibold">Elérhetőség:</p>
                    <p class="mt-1">{{ $temporarySurvey->contact ?: 'Nincs megadva' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!$temporarySurvey->is_completed)
<div class="admin-card">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Megtekintés a felhasználó szemszögéből</h2>

    <p class="mb-4">Az alábbi link megnyitásával megtekintheti a kérdőívet úgy, ahogy a felhasználó látja, az eddig kitöltött adatokkal:</p>

    <div class="bg-gray-50 p-4 rounded flex items-center justify-between">
        <div class="overflow-x-auto">
            <a href="{{ route('survey.form', ['uuid' => $temporarySurvey->uuid]) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                {{ route('survey.form', ['uuid' => $temporarySurvey->uuid]) }}
            </a>
        </div>
        <a href="{{ route('survey.form', ['uuid' => $temporarySurvey->uuid]) }}" target="_blank" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition ml-4 whitespace-nowrap">
            Megnyitás
        </a>
    </div>
</div>
@endif
@endsection
