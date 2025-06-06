@extends('layouts.app')
@section('title', 'Kapcsolat')

@section('content')
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold mb-4 text-gray-800">Kapcsolat</h1>
                <div class="w-24 h-1 bg-secondary-color mx-auto mb-6"></div>
                <p class="text-xl text-gray-600">
                    Kérdése van? Szívesen segítünk! Vegye fel velünk a kapcsolatot az alábbi elérhetőségeken.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-1 gap-8 mb-12">
                {{-- <div class="bg-gray-50 p-8 rounded-lg shadow-md cultural-border">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Elérhetőségeink</h2> --}}

                    {{-- <div class="space-y-6"> --}}
                        {{-- <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-secondary-color mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800">E-mail</h3>
                                <p class="text-gray-600">info@kulturaliskutatas.hu</p>
                            </div>
                        </div> --}}

                        {{-- <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-secondary-color mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800">Telefon</h3>
                                <p class="text-gray-600">+36 1 234 5678</p>
                            </div>
                        </div> --}}

                        {{-- <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-secondary-color mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800">Iroda</h3>
                                <p class="text-gray-600">1052 Budapest, Kultúra utca 123.</p>
                            </div>
                        </div> --}}

                        {{-- <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-secondary-color mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800">Nyitvatartás</h3>
                                <p class="text-gray-600">Hétfő - Péntek: 9:00 - 17:00</p>
                            </div>
                        </div> --}}
                    {{-- </div> --}}

                    {{-- <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Kövessen minket</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="text-secondary-color hover:text-opacity-80 transition">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="text-secondary-color hover:text-opacity-80 transition">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                </svg>
                            </a>
                            <a href="#" class="text-secondary-color hover:text-opacity-80 transition">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div> --}}
                {{-- </div> --}}

                <div class="bg-gray-50 p-8 rounded-lg shadow-md cultural-border">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Küldjön üzenetet</h2>

                    @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block font-semibold text-gray-700 mb-1">Név<span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition"
                                value="{{ old('name') }}" />
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block font-semibold text-gray-700 mb-1">E-mail<span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" required
                                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition"
                                value="{{ old('email') }}" />
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="block font-semibold text-gray-700 mb-1">Tárgy<span class="text-red-500">*</span></label>
                            <input type="text" name="subject" id="subject" required
                                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition"
                                value="{{ old('subject') }}" />
                            @error('subject')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block font-semibold text-gray-700 mb-1">Üzenet<span class="text-red-500">*</span></label>
                            <textarea name="message" id="message" rows="5" required
                                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="text-right pt-2">
                            <button type="submit" class="px-6 py-3 bg-secondary-color hover:bg-opacity-90 text-white rounded-md font-semibold shadow-md transition duration-300">
                                Üzenet küldése
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- <div class="bg-white p-8 rounded-lg shadow-md mb-12">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Irodánk helye</h2>
                <div class="h-80 w-full bg-gray-200 rounded relative overflow-hidden">
                    <!-- Itt egy térképet jelenítünk meg, de most csak egy placeholder-t használunk -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <p class="text-gray-600">Térkép betöltése...</p>
                    </div>
                </div>
            </div> --}}

            <div class="bg-gray-50 p-8 rounded-lg shadow-md cultural-border">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Gyakran Ismételt Kérdések</h2>

                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-4">
                        <button class="flex justify-between items-center w-full text-left font-semibold text-gray-800">
                            <span>Meddig tart a kutatás?</span>
                            <svg class="h-5 w-5 text-secondary-color" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="mt-2 text-gray-600">
                            <p>A kutatás adatgyűjtési fázisa 2025. április 30-ig tart, az eredmények feldolgozása és közzététele 2025 júniusára várható azok számára, akik kitöltötték az űrlapot.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button class="flex justify-between items-center w-full text-left font-semibold text-gray-800">
                            <span>Kik vehetnek részt a kutatásban?</span>
                            <svg class="h-5 w-5 text-secondary-color" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="mt-2 text-gray-600">
                            <p>Minden magyarországi kulturális és művelődési intézmény képviselője kitöltheti a kérdőívet. Ez magában foglalja a művelődési házakat, kulturális központokat, könyvtárakat, múzeumokat és egyéb közművelődési intézményeket.</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <button class="flex justify-between items-center w-full text-left font-semibold text-gray-800">
                            <span>Kapok visszajelzést a kutatás eredményeiről?</span>
                            <svg class="h-5 w-5 text-secondary-color" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="mt-2 text-gray-600">
                            <p>Igen, ha a kérdőív kitöltésekor megadja elérhetőségét, akkor a kutatás lezárását követően küldünk Önnek egy összefoglalót az eredményekről.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
