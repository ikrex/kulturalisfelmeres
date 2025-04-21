@extends('layouts.admin')
@section('title', 'Folyamatban lévő kérdőívek')
@section('page-title', 'Folyamatban lévő kérdőívek')

@section('content')
<div class="admin-card">
    <div class="flex justify-between items-center mb-6">
        <div>
            <form action="{{ route('admin.temporary-surveys.index') }}" method="GET" class="flex space-x-2">
                <div class="flex">
                    <input type="text" name="search" placeholder="Keresés..." value="{{ request('search') }}"
                        class="border border-gray-300 rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-color">
                    <button type="submit" class="bg-primary-color text-white px-4 py-2 rounded-r hover:bg-opacity-90 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-4">
        <div class="flex space-x-2">
            <a href="{{ route('admin.temporary-surveys.index') }}" class="inline-flex items-center px-4 py-2 rounded {{ request()->has('completed') ? 'bg-gray-200 text-gray-700' : 'bg-primary-color text-white' }}">
                Folyamatban lévő
            </a>
            <a href="{{ route('admin.temporary-surveys.index', ['completed' => 1]) }}" class="inline-flex items-center px-4 py-2 rounded {{ request()->has('completed') ? 'bg-primary-color text-white' : 'bg-gray-200 text-gray-700' }}">
                Befejezett
            </a>
        </div>
    </div>

    @if($temporarySurveys->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UUID</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Cím</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Elkezdve</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utolsó módosítás</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Állapot</th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Műveletek</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($temporarySurveys as $survey)
                <tr>
                    <td class="py-3 px-4 whitespace-nowrap">{{ Str::limit($survey->uuid, 10) }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">{{ $survey->ip_address }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">{{ $survey->created_at->format('Y-m-d H:i') }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">{{ $survey->updated_at->format('Y-m-d H:i') }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">
                        @if($survey->is_completed)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Befejezett
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Folyamatban
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-4 whitespace-nowrap">
                        <a href="{{ route('admin.temporary-surveys.show', $survey->uuid) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                            Megtekintés
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $temporarySurveys->links() }}
    </div>
    @else
    <div class="text-center py-10">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="text-lg font-semibold text-gray-500">Nincs megjeleníthető adat</h3>
        <p class="text-gray-500 mt-2">{{ request()->has('completed') ? 'Nincsenek befejezett ideiglenes kitöltések.' : 'Jelenleg nincsenek folyamatban lévő kitöltések.' }}</p>
    </div>
    @endif
</div>
@endsection
