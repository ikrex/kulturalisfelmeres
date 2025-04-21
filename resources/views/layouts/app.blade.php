<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Kulturális Kutatás</title>
    <meta name="description" content="Kulturális és művelődési intézmények kutatása">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN - egyszerűbb megoldás -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Egyedi CSS -->
    <style>
        :root {
            --primary-color: #4F5D75;
            --secondary-color: #BF4B75;
            --accent-color: #E5B83E;
            --text-color: #2D3142;
            --light-color: #FFFFFF;
            --bg-color: #F8F7F4;
        }

        body {
            font-family: 'Raleway', sans-serif;
            color: var(--text-color);
            background-color: var(--bg-color);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: #A0395F;
            border-color: #A0395F;
        }

        .header-pattern {
            background-color: var(--primary-color);
            background-image: url("data:image/svg+xml,%3Csvg width='52' height='26' viewBox='0 0 52 26' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e5b83e' fill-opacity='0.15'%3E%3Cpath d='M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4v2c-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6zm25.464-1.95l8.486 8.486-1.414 1.414-8.486-8.486 1.414-1.414z' /%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .footer {
            background-color: var(--primary-color);
            color: var(--light-color);
        }

        .cultural-border {
            border-top: 3px solid var(--accent-color);
        }

        .bg-primary-color {
            background-color: var(--primary-color);
        }

        .bg-secondary-color {
            background-color: var(--secondary-color);
        }

        .bg-accent-color {
            background-color: var(--accent-color);
        }

        .text-primary-color {
            color: var(--primary-color);
        }

        .text-secondary-color {
            color: var(--secondary-color);
        }

        .text-accent-color {
            color: var(--accent-color);
        }

        .border-primary-color {
            border-color: var(--primary-color);
        }

        .border-secondary-color {
            border-color: var(--secondary-color);
        }

        .border-accent-color {
            border-color: var(--accent-color);
        }
    </style>

    <!-- Egyedi stílusok -->
    @stack('styles')
</head>
<body>
    <header class="header-pattern py-4 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-white text-2xl font-bold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h6v4H7V5zm8 8V7a1 1 0 00-1-1H6a1 1 0 00-1 1v6a1 1 0 001 1h8a1 1 0 001-1z" clip-rule="evenodd" />
                    </svg>
                    Kulturális Kutatás
                </a>
                <nav>
                    <ul class="flex space-x-6">
                        <li><a href="{{ route('survey.form') }}" class="text-white hover:text-gray-200">Kérdőív</a></li>
                        <li><a href="{{ route('about') }}" class="text-white hover:text-gray-200">Rólunk</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white hover:text-gray-200">Kapcsolat</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer py-8 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Kulturális Kutatás</h3>
                    <p class="text-gray-300">
                        Célunk a magyarországi kulturális és művelődési intézmények működésének támogatása, fejlesztése és elemzése.
                    </p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Kapcsolat</h3>
                    <p class="text-gray-300">
                        Email: info@kulturaliskutatas.hu<br>
                        Telefon: +36 1 234 5678
                    </p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Kövess minket</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-gray-300">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="text-white hover:text-gray-300">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="#" class="text-white hover:text-gray-300">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Kulturális Kutatás. Minden jog fenntartva.</p>
            </div>
        </div>
    </footer>

    <!-- Alapvető jQuery betöltése -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Egyedi JS kód a kérdőív ideiglenes mentéséhez -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // UUID generálása vagy meglévő használata
            let surveyUuid = document.getElementById('uuid')?.value || '';

            // Form elemek gyűjtése
            const formElements = document.querySelectorAll('#survey-form input, #survey-form textarea, #survey-form select');

            // CSRF token lekérése
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Ideiglenes mentés funkció
            const saveTemporary = debounce(() => {
                const formData = new FormData(document.getElementById('survey-form'));

                // UUID hozzáadása, ha van
                if (surveyUuid) {
                    formData.append('uuid', surveyUuid);
                }

                // Adatok elküldése AJAX-szal
                fetch('/kerdoiv/ideiglenes-mentes', {
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

                        // Állapot jelzése
                        updateSaveStatus(true);
                    } else {
                        // Hiba jelzése
                        updateSaveStatus(false);
                    }
                })
                .catch(error => {
                    console.error('Ideiglenes mentés hiba:', error);
                    updateSaveStatus(false);
                });
            }, 1000); // 1 másodperces késleltetés

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

            // Mentési állapot frissítése
            function updateSaveStatus(success) {
                const saveStatus = document.getElementById('save-status');
                if (!saveStatus) return;

                if (success) {
                    saveStatus.textContent = 'Adatok ideiglenesen mentve';
                    saveStatus.className = 'text-green-600 text-sm transition-opacity duration-1000';

                    // Elhalványítjuk 3 másodperc után
                    setTimeout(() => {
                        saveStatus.className = 'text-green-600 text-sm opacity-0 transition-opacity duration-1000';
                    }, 3000);
                } else {
                    saveStatus.textContent = 'Mentési hiba történt';
                    saveStatus.className = 'text-red-600 text-sm transition-opacity duration-1000';
                }
            }

            // Eseménykezelők hozzáadása minden form elemhez
            if (formElements.length > 0) {
                formElements.forEach(element => {
                    element.addEventListener('blur', saveTemporary);
                    element.addEventListener('change', saveTemporary);
                });
            }
        });
    </script>

    <!-- További egyedi scriptek -->
    @stack('scripts')
</body>
</html>
