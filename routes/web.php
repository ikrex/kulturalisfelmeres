<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TemporarySurveyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Főoldal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rólunk oldal
Route::get('/rolunk', [HomeController::class, 'about'])->name('about');

// Kérdőív útvonalak
Route::get('/kerdoiv', [SurveyController::class, 'showForm'])->name('survey.form');
Route::post('/kerdoiv/bekuldese', [SurveyController::class, 'submitForm'])->name('survey.submit');
Route::get('/kerdoiv/koszonjuk', [SurveyController::class, 'showThanks'])->name('survey.thanks');
Route::post('/kerdoiv/ideiglenes-mentes', [TemporarySurveyController::class, 'saveTemporary'])->name('survey.temporary.save');

// Kapcsolat útvonalak
Route::get('/kapcsolat', [ContactController::class, 'showContactForm'])->name('contact');
Route::post('/kapcsolat/kuldes', [ContactController::class, 'sendContactForm'])->name('contact.send');

// Auth útvonalak (Laravel 12-es szintaxissal)
// Az auth útvonalakat a routes/auth.php fájlba illesztjük
// Ezt a Laravel 12 telepítő automatikusan hozzáadja

// Admin útvonalak
// Laravel 12-ben a middleware beállítását közvetlenül használjuk
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Irányítópult
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Beküldött kérdőívek
    Route::get('/surveys', [SurveyController::class, 'adminIndex'])->name('surveys.index');
    Route::get('/surveys/{id}', [SurveyController::class, 'adminShow'])->name('surveys.show');
    Route::get('/surveys/export', [SurveyController::class, 'export'])->name('surveys.export');

    // Ideiglenes kérdőívek
    Route::get('/temporary-surveys', [TemporarySurveyController::class, 'adminIndex'])->name('temporary-surveys.index');
    Route::get('/temporary-surveys/{uuid}', [TemporarySurveyController::class, 'adminShow'])->name('temporary-surveys.show');

    // Felhasználók
    Route::resource('users', UserController::class);
});
