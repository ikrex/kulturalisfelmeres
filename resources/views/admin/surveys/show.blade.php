@extends('layouts.admin')
@section('title', 'Kérdőív részletei')
@section('page-title', 'Kérdőív részletei')

@section('page-actions')
<a href="{{ route('admin.surveys.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
    </svg>
    Vissza a listához
</a>
@endsection

@section('content')
<div class="admin-card mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Kérdőív információk</h2>
        <div class="bg-gray-100 rounded-lg px-4 py-2 text-sm text-gray-700">
            Beküldve: {{ $survey->created_at->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Azonosítók</h3>
            <div class="bg-gray-50 p-4 rounded mb-4">
                <p><span class="font-semibold">ID:</span> {{ $survey->id }}</p>
                <p><span class="font-semibold">UUID:</span> {{ $survey->uuid }}</p>
                <p><span class="font-semibold">IP Cím:</span> {{ $survey->ip_address }}</p>
            </div>

            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Kapcsolati adatok</h3>
            <div class="bg-gray-50 p-4 rounded">
                <p><span class="font-semibold">Intézmény neve:</span> {{ $survey->institution_name }}</p>
                <p><span class="font-semibold">Kapcsolat:</span> {{ $survey->contact ?: 'Nincs megadva' }}</p>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Válaszok</h3>
            <div class="bg-gray-50 p-4 rounded">
                <div class="mb-4">
                    <p class="font-semibold">Használt rendezvényszervező szoftver(ek):</p>
                    <p class="mt-1">{{ $survey->event_software ?: 'Nincs megadva' }}</p>
                </div>

                <div class="mb-4">
                    <p class="font-semibold">Milyen nehézségeket okoz a statisztikák, kimutatások készítése?</p>
                    <p class="mt-1">{{ $survey->statistics_issues ?: 'Nincs megadva' }}</p>
                </div>

                <div class="mb-4">
                    <p class="font-semibold">Milyen kihívások vannak az információáramlásban és szervezésben?</p>
                    <p class="mt-1">{{ $survey->communication_issues ?: 'Nincs megadva' }}</p>
                </div>

                <div class="mb-4">
                    <p class="font-semibold">Átláthatók-e az események és azok dokumentálása?</p>
                    <p class="mt-1">{{ $survey->event_transparency ?: 'Nincs megadva' }}</p>
                </div>

                <div>
                    <p class="font-semibold">Nyitott lenne ingyenes tanácsadásra vagy módszertani segítségre?</p>
                    <p class="mt-1">{{ $survey->want_help }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@if($temporarySurvey)
<div class="admin-card">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Ideiglenes kitöltési adatok</h2>

    <div class="bg-gray-50 p-4 rounded mb-4">
        <p><span class="font-semibold">Elkezdve:</span> {{ $temporarySurvey->created_at->format('Y-m-d H:i:s') }}</p>
        <p><span class="font-semibold">Utoljára módosítva:</span> {{ $temporarySurvey->updated_at->format('Y-m-d H:i:s') }}</p>
        <p><span class="font-semibold">Kitöltési idő:</span> {{ $survey->created_at->diffForHumans($temporarySurvey->created_at, ['parts' => 2]) }}</p>
    </div>
</div>
@endif
@endsection
