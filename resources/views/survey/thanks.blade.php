@extends('layouts.app')
@section('title', 'Köszönjük a kitöltést')

@section('content')
<div class="container mx-auto max-w-3xl px-4 py-16">
    <div class="text-center mb-10">
        <div class="mb-6 flex justify-center">
            <div class="rounded-full bg-green-100 p-6 inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl md:text-4xl font-bold mb-4 text-gray-800">Köszönjük a válaszait!</h1>
        <div class="w-24 h-1 bg-secondary-color mx-auto mb-6"> </div>

        {{-- <p class="text-xl text-gray-700 mb-8">
            Válaszait sikeresen rögzítettük. Köszönjük, hogy időt szánt a kérdőív kitöltésére.
        </p> --}}

        @if(session('info'))
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 01-1-1v-4a1 1 0 112 0v4a1 1 0 01-1 1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        {{ session('info') }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        Köszönjük, hogy kitöltötte kérdőívünket! Válaszait sikeresen rögzítettük.
                    </p>
                </div>
            </div>
        </div>
    @endif


        <div class="bg-white rounded-lg shadow-md p-8 mb-10 cultural-border">
            <h2 class="text-2xl font-bold mb-4 text-primary-color">Mi történik ezután?</h2>
            <div class="space-y-4 text-left">
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-6 w-6 text-accent-color">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-gray-700">Válaszait bizalmasan kezeljük, és kizárólag összesített formában dolgozzuk fel.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 h-6 w-6 text-accent-color">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-gray-700">A kutatás eredményeiről értesítést küldünk a megadott elérhetőségre (amennyiben megadta).</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 h-6 w-6 text-accent-color">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-gray-700">Az összesített eredményeket a kutatás lezárása után közzétesszük honlapunkon.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-primary-color hover:bg-opacity-90 text-white rounded-md font-semibold shadow-md transition">
                Vissza a főoldalra
            </a>

            <a href="{{ route('about') }}" class="px-6 py-3 border border-primary-color text-primary-color hover:bg-primary-color hover:text-white rounded-md font-semibold shadow-md transition">
                Tudjon meg többet rólunk
            </a>
        </div>
    </div>
</div>
@endsection
