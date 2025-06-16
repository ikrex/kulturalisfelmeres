@extends('layouts.app')
@section('title', 'Művelődési intézményi kérdőív')

@push('scripts')
<script>
/**
 * Kérdőív ideiglenes mentésének JavaScript kódja
 * Ez a kód a Laravel 12-vel kompatibilis CSRF kezelést is tartalmazza
 */
 document.addEventListener('DOMContentLoaded', function() {
    let isFormSubmitted = false;
    const form = document.getElementById('survey-form');
    form.addEventListener('submit', function() {
        isFormSubmitted = true;
        console.log('Form beküldve, ideiglenes mentés letiltva');
    });

    // UUID generálása vagy meglévő használata
    let surveyUuid = document.getElementById('uuid')?.value || '';

    // Form elemek gyűjtése
    const formElements = document.querySelectorAll('#survey-form input, #survey-form textarea, #survey-form select');

    // CSRF token lekérése - Laravel 12-ben is ugyanúgy működik
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // "Egyéb" opciók kezelése - JAVÍTOTT RÉSZ
    const radioButtons = document.querySelectorAll('input[type="radio"]');

    // Egyéb opciók kezdeti állapotának beállítása minden csoportban
    document.querySelectorAll('input[value="other"]').forEach(otherOption => {
        const name = otherOption.getAttribute('name');
        const textareaId = name + '_other_text';
        const textarea = document.getElementById(textareaId);

        if (textarea) {
            textarea.style.display = otherOption.checked ? 'block' : 'none';
        }
    });

    // Minden radio gomb változásának figyelése
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            const name = this.getAttribute('name');
            const textareaId = name + '_other_text';
            const textarea = document.getElementById(textareaId);

            // Ha ez egy "other" opció és be van jelölve, megjelenítjük a textarea-t
            if (this.value === 'other' && this.checked && textarea) {
                textarea.style.display = 'block';
            }
            // Ha nem "other" opció, de ugyanabban a csoportban van, elrejtjük a textarea-t
            else if (this.checked && textarea) {
                textarea.style.display = 'none';
            }
        });
    });

    // Ideiglenes mentés funkció
    const saveTemporary = debounce(() => {
        if (isFormSubmitted) {
            console.log('Form már beküldve, ideiglenes mentés kihagyva');
            return;
        }

        // Az adatokat csak akkor küldjük el, ha legalább egy mező ki van töltve
        const formData = new FormData(form);

        // UUID hozzáadása, ha van
        if (surveyUuid) {
            formData.append('uuid', surveyUuid);
        }

        // Követőkód hozzáadása, ha van
        const trackingCode = document.getElementById('tracking_code')?.value;
        if (trackingCode) {
            formData.append('tracking_code', trackingCode);
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

<!-- Intézmény adatok előtöltése, ha van intézmény -->
@if(isset($institution))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Intézmény nevének előtöltése
        var institutionNameField = document.getElementById('institution_name');
        if (institutionNameField && !institutionNameField.value) {
            institutionNameField.value = "{{ $institution->name }}";
        }

        // Email cím előtöltése
        var contactField = document.getElementById('contact');
        if (contactField && !contactField.value) {
            contactField.value = "{{ $institution->email }}";
        }
    });
    </script>
@endif



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

        <!-- Követőkód hozzáadása a formhoz -->
        @if(isset($trackingCode))
            <input type="hidden" name="tracking_code" id="tracking_code" value="{{ $trackingCode }}">
        @endif

        <div>
            <label for="institution_name" class="block font-semibold text-gray-700 mb-1">Intézmény neve<span class="text-red-500">*</span></label>
            <input type="text" name="institution_name" id="institution_name" required
                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition"
                value="{{ $temporarySurvey->institution_name ?? old('institution_name') }}" />
        </div>

        <div>
            <label for="event_software" class="block font-semibold text-gray-700 mb-1">Használt rendezvényszervező szoftver(ek)<br>
                <small>Pl KalDo rendszer, google naptár, excel tábla, megosztott word dokumentum</small><br>
                ha nincs, akkor hogyan rögzítik a rendezvényeket (pl papír alapon)
            </label>
            <input type="text" name="event_software" id="event_software"
                class="mt-1 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition"
                value="{{ $temporarySurvey->event_software ?? old('event_software') }}" />
        </div>

        <!-- Információáramlás problémák -->
        <div>
            <label class="block font-semibold text-gray-700 mb-3">Információ áramlásnál gondot okozhat:</label>
            <div class="radio-group space-y-2 ml-4">
                <div>
                    <input type="radio" id="info_flow_1" name="info_flow_issues" value="telephelyek"
                        {{ ($temporarySurvey->info_flow_issues ?? old('info_flow_issues')) == 'telephelyek' ? 'checked' : '' }}>
                    <label for="info_flow_1" class="ml-2">Eltérő telephelyeken dolgoznak a kollégák</label>
                </div>
                <div>
                    <input type="radio" id="info_flow_2" name="info_flow_issues" value="munkaidő"
                        {{ ($temporarySurvey->info_flow_issues ?? old('info_flow_issues')) == 'munkaidő' ? 'checked' : '' }}>
                    <label for="info_flow_2" class="ml-2">A kollégák munkaideje nem fedi egymást, nem találkoznak személyesen</label>
                </div>
                <div>
                    <input type="radio" id="info_flow_3" name="info_flow_issues" value="félreértések"
                        {{ ($temporarySurvey->info_flow_issues ?? old('info_flow_issues')) == 'félreértések' ? 'checked' : '' }}>
                    <label for="info_flow_3" class="ml-2">Félreértések a szóbeli kommunikáció során</label>
                </div>
                <div>
                    <input type="radio" id="info_flow_4" name="info_flow_issues" value="online"
                        {{ ($temporarySurvey->info_flow_issues ?? old('info_flow_issues')) == 'online' ? 'checked' : '' }}>
                    <label for="info_flow_4" class="ml-2">A program segíti az online munkavégzést (kismamák, egyéb családi problémák esetén, családbarát munkahely kialakítás)</label>
                </div>
                <div>
                    <input type="radio" id="info_flow_other" name="info_flow_issues" value="other"
                        {{ ($temporarySurvey->info_flow_issues ?? old('info_flow_issues')) == 'other' ? 'checked' : '' }}>
                    <label for="info_flow_other" class="ml-2">Egyéb (Fejtse is ki)</label>
                </div>
                <textarea id="info_flow_issues_other_text" name="info_flow_issues_other_text"
                    class="mt-2 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition"
                    style="display: {{ ($temporarySurvey->info_flow_issues ?? old('info_flow_issues')) == 'other' ? 'block' : 'none' }};"
                    rows="3">{{ $temporarySurvey->info_flow_issues_other_text ?? old('info_flow_issues_other_text') }}</textarea>
            </div>
        </div>

        <!-- Visszakereshető rendezvény előnyei -->
        <div>
            <label class="block font-semibold text-gray-700 mb-3">Visszakereshető rendezvény előnyei:</label>
            <div class="radio-group space-y-2 ml-4">
                <div>
                    <input type="radio" id="event_tracking_1" name="event_tracking_benefits" value="partner_változás"
                        {{ ($temporarySurvey->event_tracking_benefits ?? old('event_tracking_benefits')) == 'partner_változás' ? 'checked' : '' }}>
                    <label for="event_tracking_1" class="ml-2">Rendezvény évek óta ugyanaz, de pl. a partner neve (szerződő fél) változik</label>
                </div>
                <div>
                    <input type="radio" id="event_tracking_2" name="event_tracking_benefits" value="munkakör_átadás"
                        {{ ($temporarySurvey->event_tracking_benefits ?? old('event_tracking_benefits')) == 'munkakör_átadás' ? 'checked' : '' }}>
                    <label for="event_tracking_2" class="ml-2">Kilépés esetén egyszerűsíti a munkakör átadását</label>
                </div>
                <div>
                    <input type="radio" id="event_tracking_3" name="event_tracking_benefits" value="betegség"
                        {{ ($temporarySurvey->event_tracking_benefits ?? old('event_tracking_benefits')) == 'betegség' ? 'checked' : '' }}>
                    <label for="event_tracking_3" class="ml-2">Betegség esetén sincs fennakadás, hiszen látjuk, hol tart a rendezvény szervezése</label>
                </div>
                <div>
                    <input type="radio" id="event_tracking_other" name="event_tracking_benefits" value="other"
                        {{ ($temporarySurvey->event_tracking_benefits ?? old('event_tracking_benefits')) == 'other' ? 'checked' : '' }}>
                    <label for="event_tracking_other" class="ml-2">Egyéb (Fejtse is ki)</label>
                </div>
                <textarea id="event_tracking_benefits_other_text" name="event_tracking_benefits_other_text"
                    class="mt-2 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition"
                    style="display: {{ ($temporarySurvey->event_tracking_benefits ?? old('event_tracking_benefits')) == 'other' ? 'block' : 'none' }};"
                    rows="3">{{ $temporarySurvey->event_tracking_benefits_other_text ?? old('event_tracking_benefits_other_text') }}</textarea>
            </div>
        </div>

        <!-- Statisztika, kimutatás, beszámoló előnyei -->
        <div>
            <label class="block font-semibold text-gray-700 mb-3">Statisztika, kimutatás, BESZÁMOLÓ előnyei:</label>
            <div class="radio-group space-y-2 ml-4">
                <div>
                    <input type="radio" id="stats_benefits_1" name="stats_benefits" value="friss_vélemény"
                        {{ ($temporarySurvey->stats_benefits ?? old('stats_benefits')) == 'friss_vélemény' ? 'checked' : '' }}>
                    <label for="stats_benefits_1" class="ml-2">Év közben frissen írjuk a véleményt</label>
                </div>
                <div>
                    <input type="radio" id="stats_benefits_2" name="stats_benefits" value="több_kolléga"
                        {{ ($temporarySurvey->stats_benefits ?? old('stats_benefits')) == 'több_kolléga' ? 'checked' : '' }}>
                    <label for="stats_benefits_2" class="ml-2">Több kolléga beszámolója rögzíthető</label>
                </div>
                <div>
                    <input type="radio" id="stats_benefits_3" name="stats_benefits" value="ksh_szűrés"
                        {{ ($temporarySurvey->stats_benefits ?? old('stats_benefits')) == 'ksh_szűrés' ? 'checked' : '' }}>
                    <label for="stats_benefits_3" class="ml-2">Év végén már csak szűrni kell a KSH adatokat</label>
                </div>
                <div>
                    <input type="radio" id="stats_benefits_4" name="stats_benefits" value="nincs_tévedés"
                        {{ ($temporarySurvey->stats_benefits ?? old('stats_benefits')) == 'nincs_tévedés' ? 'checked' : '' }}>
                    <label for="stats_benefits_4" class="ml-2">Szinte nulla a tévedés esélye</label>
                </div>
                <div>
                    <input type="radio" id="stats_benefits_other" name="stats_benefits" value="other"
                        {{ ($temporarySurvey->stats_benefits ?? old('stats_benefits')) == 'other' ? 'checked' : '' }}>
                    <label for="stats_benefits_other" class="ml-2">Egyéb (Fejtse is ki)</label>
                </div>
                <textarea id="stats_benefits_other_text" name="stats_benefits_other_text"
                    class="mt-2 w-full border border-gray-300 p-3 rounded-md focus:ring focus:ring-primary-color focus:border-primary-color transition"
                    style="display: {{ ($temporarySurvey->stats_benefits ?? old('stats_benefits')) == 'other' ? 'block' : 'none' }};"
                    rows="3">{{ $temporarySurvey->stats_benefits_other_text ?? old('stats_benefits_other_text') }}</textarea>
            </div>
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
            A kitöltési határidő: <strong class="text-secondary-color">2025. május 30 (péntek).</strong>
        </p>
    </div>
</div>
@endsection
