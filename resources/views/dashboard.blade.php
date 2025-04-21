@extends('layouts.admin')
@section('title', 'Irányítópult')
@section('page-title', 'Irányítópult')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="admin-card">
        <div class="flex items-center">
            <div class="rounded-full bg-green-100 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Beküldött kérdőívek</h3>
                <p class="text-3xl font-bold">{{ $completedSurveysCount }}</p>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="flex items-center">
            <div class="rounded-full bg-yellow-100 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Folyamatban lévő kérdőívek</h3>
                <p class="text-3xl font-bold">{{ $inProgressSurveysCount }}</p>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="flex items-center">
            <div class="rounded-full bg-blue-100 p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Felhasználók</h3>
                <p class="text-3xl font-bold">{{ $usersCount }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="admin-card">
        <h2 class="text-xl font-bold mb-4">Legutóbbi kérdőívek</h2>

        @if ($recentSurveys->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intézmény</th>
                            <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dátum</th>
                            <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Művelet</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($recentSurveys as $survey)
                            <tr>
                                <td class="py-3 px-3 whitespace-nowrap">{{ Str::limit($survey->institution_name, 30) }}</td>
                                <td class="py-3 px-3 whitespace-nowrap">{{ $survey->created_at->format('Y-m-d H:i') }}</td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    <a href="{{ route('admin.surveys.show', $survey->id) }}" class="text-indigo-600 hover:text-indigo-900">Megtekintés</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('admin.surveys.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Összes megtekintése &rarr;</a>
            </div>
        @else
            <p class="text-gray-500 italic">Még nincsenek beküldött kérdőívek.</p>
        @endif
    </div>

    <div class="admin-card">
        <h2 class="text-xl font-bold mb-4">Folyamatban lévő kitöltések</h2>

        @if ($inProgressSurveys->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP cím</th>
                            <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Elkezdve</th>
                            <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Művelet</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($inProgressSurveys as $tempSurvey)
                            <tr>
                                <td class="py-3 px-3 whitespace-nowrap">{{ $tempSurvey->ip_address }}</td>
                                <td class="py-3 px-3 whitespace-nowrap">{{ $tempSurvey->created_at->format('Y-m-d H:i') }}</td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    <a href="{{ route('admin.temporary-surveys.show', $tempSurvey->uuid) }}" class="text-indigo-600 hover:text-indigo-900">Megtekintés</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('admin.temporary-surveys.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Összes megtekintése &rarr;</a>
            </div>
        @else
            <p class="text-gray-500 italic">Nincsenek folyamatban lévő kitöltések.</p>
        @endif
    </div>
</div>
@endsection
