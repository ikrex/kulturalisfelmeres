@extends('layouts.app')
@section('title', 'Főoldal')

@section('content')
<div class="bg-gradient-to-b from-white to-gray-100 py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0 md:pr-10">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-primary-color">Kulturális Kutatás</h1>
                <p class="text-xl mb-8 text-gray-700">
                    Köszöntjük a magyarországi kulturális és művelődési intézmények működését feltérképező kutatás oldalán.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('survey.form') }}" class="px-8 py-4 bg-secondary-color text-white rounded-md font-semibold shadow-lg transition transform hover:scale-105 text-center">
                        Kérdőív kitöltése
                    </a>
                    <a href="{{ route('about') }}" class="px-8 py-4 bg-white border border-primary-color text-primary-color rounded-md font-semibold shadow transition hover:bg-primary-color hover:text-white text-center">
                        Tudjon meg többet
                    </a>
                </div>
            </div>
            <div class="md:w-1/2">
                <div class="relative">
                    <div class="absolute inset-0 bg-accent-color rounded-lg transform rotate-3"></div>
                    <img src="{{ asset('images/culture-hero.jpg') }}" alt="Kulturális intézmény" class="relative z-10 rounded-lg shadow-xl max-w-full h-auto" onerror="this.src='https://via.placeholder.com/600x400?text=Kulturális+Kutatas';this.onerror='';">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4 text-gray-800">A kutatásunkról</h2>
            <div class="w-24 h-1 bg-secondary-color mx-auto mb-6"></div>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Célunk a hazai kulturális intézmények működési hatékonyságának fejlesztése, modern módszerek és eszközök bevezetésének támogatása.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition transform hover:-translate-y-1 cultural-border">
                <div class="text-secondary-color mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Felmérés</h3>
                <p class="text-gray-600">
                    Kérdőívünk segítségével feltérképezzük a jelenlegi működési mechanizmusokat, kihívásokat és igényeket.
                </p>
            </div>

            <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition transform hover:-translate-y-1 cultural-border">
                <div class="text-secondary-color mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Elemzés</h3>
                <p class="text-gray-600">
                    Az összegyűjtött adatokat szakértőink elemzik, hogy pontos képet kapjunk a szektor helyzetéről.
                </p>
            </div>

            <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition transform hover:-translate-y-1 cultural-border">
                <div class="text-secondary-color mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Megoldások</h3>
                <p class="text-gray-600">
                    A kutatási eredmények alapján szakmai támogatást és fejlesztési javaslatokat kínálunk.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4 text-gray-800">Miért fontos a részvétel?</h2>
            <div class="w-24 h-1 bg-secondary-color mx-auto mb-6"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg p-8 shadow-md">
                <div class="flex items-start mb-6">
                    <div class="bg-accent-color rounded-full p-2 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2 text-gray-800">Hatékonyságnövelés</h3>
                        <p class="text-gray-600">
                            A kutatás eredményei alapján célzott fejlesztési javaslatokat készítünk a működési hatékonyság növelésére.
                        </p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="bg-accent-color rounded-full p-2 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2 text-gray-800">Forrásoptimalizálás</h3>
                        <p class="text-gray-600">
                            Segítünk megtalálni azokat a területeket, ahol a meglévő forrásokat hatékonyabban lehet felhasználni.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-8 shadow-md">
                <div class="flex items-start mb-6">
                    <div class="bg-accent-color rounded-full p-2 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2 text-gray-800">Szakmai közösség</h3>
                        <p class="text-gray-600">
                            Összekapcsoljuk a hasonló kihívásokkal küzdő intézményeket, hogy tanulhassanak egymás tapasztalataiból.
                        </p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="bg-accent-color rounded-full p-2 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2 text-gray-800">Innovatív megoldások</h3>
                        <p class="text-gray-600">
                            Bemutatjuk a legújabb technológiai és módszertani megoldásokat, amelyek segíthetik a kulturális szféra fejlődését.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('survey.form') }}" class="px-8 py-4 bg-secondary-color text-white rounded-md font-semibold shadow-lg transition transform hover:scale-105 inline-block">
                Részt veszek a kutatásban
            </a>
        </div>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4 text-gray-800">Határidő</h2>
            <div class="w-24 h-1 bg-secondary-color mx-auto mb-6"></div>
        </div>

        <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-lg border-t-4 border-accent-color">
            <div class="flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-accent-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-center mb-4 text-gray-800">Kérdőív kitöltési határidő</h3>
            <p class="text-xl text-center mb-6 text-gray-600">
                <span class="font-bold text-secondary-color">2025. április 30.</span>
            </p>
            <p class="text-gray-600 text-center">
                Kérjük, hogy a fenti határidőig töltse ki a kérdőívünket, hogy az Ön válaszai is bekerülhessenek az elemzésünkbe.
            </p>
        </div>
    </div>
</div>
@endsection
