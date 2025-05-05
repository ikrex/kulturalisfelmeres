<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Felmérés művelődési intézmények működéséről</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .highlight {
            font-weight: bold;
        }
        ul {
            padding-left: 20px;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tisztelt {{ $institution->name }} Intézményvezető!</h2>

        <p>Az Ön által vezetett intézmény meghatározó szerepet tölt be a közösségi és kulturális élet formálásában. Ahhoz, hogy a művelődési házak munkáját hatékonyan támogassuk, egy célzott felmérést indítunk, melynek keretében a <span class="highlight">napi működés, szervezési gyakorlatok és nehézségek</span> mélyebb megértésére törekszünk.</p>

        <p>A www.kulturaliskutatas.hu oldalon elérhető kérdőív segítségével az alábbi területeket szeretnénk feltérképezni:</p>

        <ul>
            <li><span class="highlight">Milyen rendezvényszervező programokat, szoftvereket</span> használnak az intézmények, és mennyire támogatják ezek a mindennapi munkát?</li>
            <li>Hogyan zajlik az intézményen belüli és kívüli <span class="highlight">információáramlás</span> – milyen akadályok lassítják vagy bonyolítják azt?</li>
            <li>Mennyire <span class="highlight">átlátható és visszakereshető</span> az intézmény rendezvénynaptára, eseményei és belső folyamatai?</li>
            <li>Milyen <span class="highlight">nehézségek merülnek fel statisztikák, kimutatások készítésekor</span> – például havi vagy éves beszámolók, fenntartói jelentések esetében?</li>
            <li>Melyek a leggyakoribb <span class="highlight">szervezési, adminisztratív kihívások</span>, amelyek időt, energiát és kapacitást emésztenek fel?</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ route('tracking.survey', ['code' => $institution->tracking_code]) }}" class="btn">Kérdőív megnyitása</a>
        </div>

        <p><span class="highlight">Kitöltési határidő: 2025. Május 30. (péntek), éjfél</span></p>

        <p>A felmérés eredményei alapján lehetőség nyílik arra, hogy a jövőben olyan <span class="highlight">gyakorlati, testre szabott megoldásokat és módszertani segítséget</span> tudjunk nyújtani, amelyek valódi támogatást jelentenek az intézményeknek.</p>

        <p><span class="highlight">Az ingyenes szakmai tanácsadásra, visszajelzésre és támogatási javaslatokra kizárólag azok az intézmények lesznek jogosultak, akik a fenti határidőig kitöltik a kérdőívet.</span></p>

        <p>A kitöltés kb. <span class="highlight">5–7 percet vesz igénybe</span>, a válaszokat <span class="highlight">bizalmasan kezeljük</span>, és azokat kizárólag összesített formában használjuk fel.</p>

        <p>Kérjük, segítse munkánkat, és járuljon hozzá a művelődési intézmények közös fejlődéséhez!</p>

        <p>Köszönettel és üdvözlettel:<br>
        Illés Kálmán<br>
        (kutatásvezető)</p>
        Pár partner, akivel dolgozok (nem teljes lista)<br>
        - Bálint Ágnes Kulturális Központ<br>
        - TÁNCSICS MIHÁLY MŰVELŐDÉSI HÁZ<br>
        - KONDOR BÉLA KÖZÖSSÉGI HÁZ ÉS INTÉZMÉNYEI<br>
        - Csokonai Nonprofit Kft.<br>
        - Terézvárosi Kulturális Közhasznú Nonprofit Zrt.<br>


        <div class="footer">
            <p>Ha kérdése van a felméréssel kapcsolatban, kérjük, vegye fel velünk a kapcsolatot a <a href="mailto:info@kulturaliskutatas.hu">info@kulturaliskutatas.hu</a> email címen.</p>
        </div>
    </div>

    <!-- Láthatatlan követőpixel -->
    {{-- <img src="{{ route('tracking.email', ['code' => $institution->tracking_code]) }}" width="1" height="1" alt="" style="display:none;"> --}}

    <img src="{{ url('/t/img/'.$institution->tracking_code) }}" width="1" height="1" alt="" style="display:none;">
</body>
</html>
