@extends('layouts.app')
@section('title', 'Rólunk')

@section('content')
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold mb-4 text-gray-800">Rólunk</h1>
                <div class="w-24 h-1 bg-secondary-color mx-auto mb-6"></div>
                <p class="text-xl text-gray-600">
                    Megismerheti kutatásunk célját, csapatunkat és azt, hogy miért fontos a munkánk a kulturális szféra számára.
                </p>
            </div>

            <div class="prose prose-lg max-w-none">
                <div class="bg-gray-50 p-8 rounded-lg shadow-md mb-12 cultural-border">
                    <h2 class="text-2xl font-bold mb-4">Küldetésünk</h2>
                    <p>
                        A Kulturális Kutatás projektünk célja a magyarországi kulturális és művelődési intézmények működésének feltérképezése, elemzése és fejlesztése.
                        Hisszük, hogy a kultúra megőrzése és terjesztése alapvető fontosságú társadalmunk számára, és az intézményeknek hatékonyan kell működniük
                        ahhoz, hogy betölthessék ezt a szerepet.
                    </p>
                    <p>
                        Kutatásunk során kiemelten foglalkozunk az alábbi területekkel:
                    </p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Kulturális intézmények működési hatékonyságának elemzése</li>
                        <li>Digitális átállás kihívásai és lehetőségei</li>
                        <li>Rendezvényszervezés és -dokumentálás folyamatainak optimalizálása</li>
                        <li>Információáramlás javítása az intézményeken belül és között</li>
                        <li>Statisztikai adatgyűjtés és -elemzés fejlesztése</li>
                    </ul>
                </div>

                <div class="mb-12">
                    <h2 class="text-2xl font-bold mb-6">Kutatási csapatunk</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-primary-color">
                            <div class="flex items-center mb-4">
                                <div class="h-16 w-16 rounded-full bg-primary-color flex items-center justify-center text-white text-2xl font-bold mr-4">
                                    KL
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Dr. Kovács László</h3>
                                    <p class="text-gray-600">Kutatásvezető</p>
                                </div>
                            </div>
                            <p class="text-gray-700">
                                Több mint 15 éves tapasztalattal rendelkezik a kulturális intézmények működésének elemzésében.
                                Szakterülete a szervezetfejlesztés és a digitális átállás támogatása.
                            </p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-primary-color">
                            <div class="flex items-center mb-4">
                                <div class="h-16 w-16 rounded-full bg-primary-color flex items-center justify-center text-white text-2xl font-bold mr-4">
                                    NT
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Nagy Tímea</h3>
                                    <p class="text-gray-600">Kulturális menedzser</p>
                                </div>
                            </div>
                            <p class="text-gray-700">
                                Szakmai tapasztalatát számos hazai kulturális intézményben szerezte.
                                Fő területe a rendezvényszervezés és a kulturális programok hatékony menedzselése.
                            </p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-primary-color">
                            <div class="flex items-center mb-4">
                                <div class="h-16 w-16 rounded-full bg-primary-color flex items-center justify-center text-white text-2xl font-bold mr-4">
                                    SG
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Dr. Szabó Gábor</h3>
                                    <p class="text-gray-600">Adatelemző</p>
                                </div>
                            </div>
                            <p class="text-gray-700">
                                Statisztikai és adatelemzési módszerek specialistája, aki a kulturális szféra adatalapú döntéshozatalát segíti.
                                Több egyetemmel is együttműködik kutatási programokban.
                            </p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-primary-color">
                            <div class="flex items-center mb-4">
                                <div class="h-16 w-16 rounded-full bg-primary-color flex items-center justify-center text-white text-2xl font-bold mr-4">
                                    BK
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Balogh Katalin</h3>
                                    <p class="text-gray-600">Kommunikációs szakértő</p>
                                </div>
                            </div>
                            <p class="text-gray-700">
                                A belső és külső kommunikációs folyamatok fejlesztésével foglalkozik.
                                Segít az intézményeknek hatékonyabb információáramlási stratégiák kialakításában.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-8">
                    <p class="text-gray-600 italic">
                        Partnereink támogatásával folyamatosan bővítjük kutatási tevékenységünket és fejlesztjük szolgáltatásainkat.
                    </p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg shadow-md mb-12 cultural-border">
                    <h2 class="text-2xl font-bold mb-4">Módszertanunk</h2>
                    <p>
                        Kutatásunk több lépcsőből áll, amelynek első fázisa a most folyamatban lévő kérdőíves adatgyűjtés.
                        A beérkezett válaszok elemzése után második lépcsőben mélyinterjúkat készítünk
                        a szektorban dolgozó szakemberekkel, hogy mélyebben megértsük a kihívásokat.
                    </p>
                    <p>
                        A harmadik fázisban szakértői workshopokat szervezünk, ahol az érintettek bevonásával keresünk megoldásokat
                        a feltárt problémákra. Végül, elkészítjük átfogó jelentésünket és ajánlásainkat,
                        amelyeket megosztunk minden résztvevővel és a kulturális szféra döntéshozóival.
                    </p>
                </div>

                <div class="mb-12">
                    <h2 class="text-2xl font-bold mb-6">Partnereink</h2>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="bg-white p-4 rounded shadow-md flex items-center justify-center h-24">
                            <span class="text-gray-500 font-bold text-lg">Kultúráért Alapítvány</span>
                        </div>
                        <div class="bg-white p-4 rounded shadow-md flex items-center justify-center h-24">
                            <span class="text-gray-500 font-bold text-lg">Nemzeti Művelődési Intézet</span>
                        </div>
                        <div class="bg-white p-4 rounded shadow-md flex items-center justify-center h-24">
                            <span class="text-gray-500 font-bold text-lg">Művészeti Kutatóközpont</span>
                        </div>
                        <div class="bg-white p-4 rounded shadow-md flex items-center justify-center h-24">
                            <span class="text-gray-500 font-bold text-lg">Digitális Kultúra Egyesület</span>
                        </div>
