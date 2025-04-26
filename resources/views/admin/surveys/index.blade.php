@extends('layouts.admin')

@section('title', 'Kitöltött kérdőívek')

@section('content')
<div class="container mx-auto max-w-6xl px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kitöltött kérdőívek</h1>
        <p class="text-gray-600 mt-2">Összesen: {{ $surveys->total() }} kitöltés</p>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Intézmény neve
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Információáramlás
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Visszakereshető rendezvény
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statisztika előnye
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kitöltve
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Műveletek
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($surveys as $survey)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $survey->institution_name }}</div>
                            <div class="text-sm text-gray-500">{{ $survey->contact ?: 'Nincs elérhetőség' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @switch($survey->info_flow_issues)
                                    @case('telephelyek')
                                        <span>Eltérő telephelyek</span>
                                        @break
                                    @case('munkaidő')
                                        <span>Eltérő munkaidő</span>
                                        @break
                                    @case('félreértések')
                                        <span>Félreértések</span>
                                        @break
                                    @case('online')
                                        <span>Online munkavégzés</span>
                                        @break
                                    @case('other')
                                        <span>Egyéb</span>
                                        @break
                                    @default
                                        <span>-</span>
                                @endswitch
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @switch($survey->event_tracking_benefits)
                                    @case('partner_változás')
                                        <span>Partner változás</span>
                                        @break
                                    @case('munkakör_átadás')
                                        <span>Munkakör átadás</span>
                                        @break
                                    @case('betegség')
                                        <span>Betegség kezelése</span>
                                        @break
                                    @case('other')
                                        <span>Egyéb</span>
                                        @break
                                    @default
                                        <span>-</span>
                                @endswitch
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @switch($survey->stats_benefits)
                                    @case('friss_vélemény')
                                        <span>Friss vélemények</span>
                                        @break
                                    @case('több_kolléga')
                                        <span>Kollégák beszámolói</span>
                                        @break
                                    @case('ksh_szűrés')
                                        <span>KSH adatok szűrése</span>
                                        @break
                                    @case('nincs_tévedés')
                                        <span>Nincs tévedés</span>
                                        @break
                                    @case('other')
                                        <span>Egyéb</span>
                                        @break
                                    @default
                                        <span>-</span>
                                @endswitch
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $survey->created_at->format('Y.m.d.') }}</div>
                            <div class="text-sm text-gray-500">{{ $survey->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.surveys.show', $survey->id) }}" class="text-primary-color hover:text-primary-color-dark">
                                Részletek
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $surveys->links() }}
        </div>
    </div>
</div>
@endsection
