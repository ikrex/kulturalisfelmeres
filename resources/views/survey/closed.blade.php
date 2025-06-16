@extends('layouts.app')
@section('title', 'Kérdőív lezárva')

@section('content')
<div class="container mx-auto max-w-3xl px-4 py-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold mb-4 text-gray-800">Művelődési intézményi kérdőív</h1>
        <div class="w-24 h-1 bg-secondary-color mx-auto mb-6"></div>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-md cultural-border">
        <div class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-amber-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>

            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                A kutatás lezárult
            </h2>

            <div class="bg-amber-50 border-l-4 border-amber-500 p-6 mb-6 text-left">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-500 mt-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-amber-800 mb-2">
                            Sajnáljuk, de lemaradt a kitöltési határidőről
                        </h3>
                        <p class="text-amber-700 leading-relaxed">
                            A művelődési intézményi kérdőív kitöltési lehetősége <strong>2025. május 30. (péntek) éjfélig</strong> állt rendelkezésre.
                            A kutatás adatgyűjtési szakasza lezárult, így már nem tudja beküldeni válaszait.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-6 rounded-md mb-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">
                    Köszönjük érdeklődését!
                </h3>
                <p class="text-blue-700 leading-relaxed">
                    Hálásan köszönjük, hogy időt szándékozott szánni a kérdőív kitöltésére.
                    Az összegyűjtött adatok elemzése folyamatban van, és az eredményeket
                    hamarosan közzétesszük.
                </p>
            </div>

            <div class="mt-8 space-y-4">
                <a href="{{ route('home') }}"
                   class="inline-block px-6 py-3 bg-primary-color hover:bg-opacity-90 text-white rounded-md font-semibold shadow-md transition duration-300 transform hover:scale-105">
                    Visszatérés a főoldalra
                </a>

                <div class="text-gray-600">
                    <p>Ha kérdése van a kutatással kapcsolatban, keresse fel a
                        <a href="{{ route('contact') }}" class="text-secondary-color hover:underline font-medium">
                            kapcsolat oldalt
                        </a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Információs doboz a határidőről -->
    <div class="mt-8 p-6 bg-white rounded-lg shadow-md text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-gray-700">
            A kitöltési határidő: <strong class="text-red-500">2025. május 30 (péntek) 23:59</strong> - <em>LEJÁRT</em>
        </p>
    </div>
</div>
@endsection
