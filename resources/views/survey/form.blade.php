@extends('layouts.app')
@section('title', 'Művelődési intézményi kérdőív')

@push('scripts')
<script>
/**
 * Kérdőív ideiglenes mentésének JavaScript kódja
 * Ez a kód a Laravel 12-vel kompatibilis CSRF kezelést is tartalmazza
 */
document.addEventListener('DOMContentLoaded', function() {
    // UUID generálása vagy meglévő használata
    let surveyUuid = document.getElementById('uuid')?.value || '';

    // Form elemek gyűjtése
    const formElements = document.querySelectorAll('#survey-form input, #survey-form textarea, #survey-form select');

    // CSRF token lekérése - Laravel 12-ben is ugyanúgy működik
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Ideiglenes mentés funkció
    const saveTemporary = debounce(() => {
        // Az adatokat csak akkor küldjük el, ha legalább egy mező ki van töltve
        const form = document.getElementById('survey-form');
        const formData = new FormData(form);

        // UUID hozzáadása, ha van
        if (surveyUuid) {
            formData.append('uuid', surveyUuid);
        }

        // Teljes URL létrehozása, hogy biztosan a megfelelő helyre menjen a kérés
        const url = window.location.origin + '/kerdoiv/ideiglenes-mentes';

        // Adatok elküldése AJAX-szal
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // UUID mentése a későbbi használatra
                surveyUuid = data.uuid;

                // UUID mező értékének beállítása
                document.getElementById('uuid').value = surveyUuid;

                // Nem adunk visszajelzést a sikeres mentésről, mert azt már látja a felhasználó
                // a form tetején lévő tájékoztatásban
            } else {
                // Csak hiba esetén jelzünk
                console.error('Mentési hiba történt:', data.message || 'Ismeretlen hiba');
            }
        })
        .catch(error => {
            console.error('Adatok küldési hiba:', error);
        });
    }, 2000); // 2 másodperces késleltetés

    // Debounce függvény (késleltetéshez)
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }

    // Eseménykezelők hozzáadása minden form elemhez
    formElements.forEach(element => {
        if (element.getAttribute('type') !== 'hidden' && element.getAttribute('name') !== '_token') {
            element.addEventListener('blur', saveTemporary);
            element.addEventListener('change', saveTemporary);
        }
    });

    // Debug infó
    console.log('Ideiglenes mentés inicializálva. Endpoint:', window.location.origin + '/kerdoiv/ideiglenes-mentes');
});
</script>
@endpush

@section('content')
<div class="container mx-auto max-w-3xl px-4 py-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold mb-4 text-gray-800">Művelődési intézményi kérdőív</h1>
        <div class="w-24 h-1 bg-secondary-color mx-auto mb-6"></div>
        <p class="text-lg text-gray-700">
            Köszönjük, hogy időt szán a kérdőív kitöltésére. A válaszokat bizalmasan kezeljük,
            azokat kizárólag összesített formában dolgozzuk fel.
        </p>
    </div>

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    Kérjük, javítsa a következő hibákat:
                </h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form id="survey-form" action="{{ route('survey.submit') }}" method="POST" class="space-y-6 bg-white p-8 rounded-lg shadow-md cultural-border">
        @csrf
        <input type="hidden" name="uuid" id="uuid" value="{{ $uuid ?? '' }}">

        {{-- <div class="bg-blue-50 p-3 rounded border border-blue-100 mb-4">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <span class="text-blue-800 text-sm">Kitöltés közben automatikusan mentjük az adatokat - bármikor visszatérhet később a kitöltés folytatásához.</span>
            </div>
        </div> --}}

        <div>
            <label for="institution_name" class="block font-semibold text-gray-700 mb-1">Intézmény neve<span class="text-red-500">*</span></label>
            <input type="text" name="institution_name" id="institution_name" required
                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition"
                value="{{ $temporarySurvey->institution_name ?? old('institution_name') }}" />
        </div>

        <div>
            <label for="event_software" class="block font-semibold text-gray-700 mb-1">Használt rendezvényszervező szoftver(ek)</label>
            <input type="text" name="event_software" id="event_software"
                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition"
                value="{{ $temporarySurvey->event_software ?? old('event_software') }}" />
        </div>

        <div>
            <label for="statistics_issues" class="block font-semibold text-gray-700 mb-1">Milyen nehézségeket okoz a statisztikák, kimutatások készítése?</label>
            <textarea name="statistics_issues" id="statistics_issues" rows="3"
                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition">{{ $temporarySurvey->statistics_issues ?? old('statistics_issues') }}</textarea>
        </div>

        <div>
            <label for="communication_issues" class="block font-semibold text-gray-700 mb-1">Milyen kihívások vannak az információáramlásban és szervezésben?</label>
            <textarea name="communication_issues" id="communication_issues" rows="3"
                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition">{{ $temporarySurvey->communication_issues ?? old('communication_issues') }}</textarea>
        </div>

        <div>
            <label for="event_transparency" class="block font-semibold text-gray-700 mb-1">Átláthatók-e az események és azok dokumentálása?</label>
            <textarea name="event_transparency" id="event_transparency" rows="3"
                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition">{{ $temporarySurvey->event_transparency ?? old('event_transparency') }}</textarea>
        </div>

        <div>
            <label for="want_help" class="block font-semibold text-gray-700 mb-1">Nyitott lenne ingyenes tanácsadásra vagy módszertani segítségre?<span class="text-red-500">*</span></label>
            <select name="want_help" id="want_help"
                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition">
                <option value="igen" {{ ($temporarySurvey->want_help ?? old('want_help')) == 'igen' ? 'selected' : '' }}>Igen</option>
                <option value="nem" {{ ($temporarySurvey->want_help ?? old('want_help')) == 'nem' ? 'selected' : '' }}>Nem</option>
                <option value="bizonytalan" {{ ($temporarySurvey->want_help ?? old('want_help')) == 'bizonytalan' ? 'selected' : '' }}>Bizonytalan</option>
            </select>
        </div>

        <div>
            <label for="contact" class="block font-semibold text-gray-700 mb-1">Elérhetőség (e-mail vagy telefonszám, ha szeretne visszajelzést kapni)</label>
            <input type="text" name="contact" id="contact"
                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition"
                value="{{ $temporarySurvey->contact ?? old('contact') }}" />
        </div>

        <div class="pt-4 text-right">
            <button type="submit" class="px-6 py-3 bg-secondary-color hover:bg-opacity-90 text-white rounded-md font-semibold shadow-md transition duration-300 transform hover:scale-105">
                Kérdőív beküldése
            </button>
        </div>
    </form>

    <div class="mt-8 p-6 bg-white rounded-lg shadow-md text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-color mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-gray-700">
            A kitöltési határidő: <strong class="text-secondary-color">2025. április 30.</strong>
        </p>
    </div>
</div>
@endsection
