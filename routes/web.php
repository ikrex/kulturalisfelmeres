<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TemporarySurveyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Főoldal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rólunk oldal
Route::get('/rolunk', [HomeController::class, 'about'])->name('about');

// Kérdőív útvonalak
Route::get('/kerdoiv', [SurveyController::class, 'showForm'])->name('survey.form');
Route::post('/kerdoiv/bekuldese', [SurveyController::class, 'submitForm'])->name('survey.submit');
Route::get('/kerdoiv/koszonjuk', [SurveyController::class, 'showThanks'])->name('survey.thanks');
// Route::post('/kerdoiv/ideiglenes-mentes', [TemporarySurveyController::class, 'saveTemporary'])->name('survey.temporary.save');
Route::post('/kerdoiv/ideiglenes-mentes', [SurveyController::class, 'saveTemporary'])->name('survey.temporary.save');

// Kapcsolat útvonalak
Route::get('/kapcsolat', [ContactController::class, 'showContactForm'])->name('contact');
Route::post('/kapcsolat/kuldes', [ContactController::class, 'sendContactForm'])->name('contact.send');


// Nyomkövető útvonalak
Route::get('/t/img/{code}', [App\Http\Controllers\TrackingController::class, 'trackEmail'])->name('tracking.email');
Route::get('/t/survey/{code}', [App\Http\Controllers\TrackingController::class, 'redirectToSurvey'])->name('tracking.survey');




// web.php fájlban, a többi route mellett

// Profil szerkesztése
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});






// Auth útvonalak (Laravel 12-es szintaxissal)
// Az auth útvonalakat a routes/auth.php fájlba illesztjük
// Ezt a Laravel 12 telepítő automatikusan hozzáadja


Auth::routes();
// Admin útvonalak
// Laravel 12-ben a middleware beállítását közvetlenül használjuk
// Admin dashboard
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    // Kérdőívek kezelése
    Route::get('/surveys/export', [App\Http\Controllers\AdminController::class, 'exportSurveys'])->name('surveys.export');
    Route::get('/surveys', [App\Http\Controllers\AdminController::class, 'surveys'])->name('surveys');
    Route::get('/surveys/{id}', [App\Http\Controllers\AdminController::class, 'showSurvey'])->name('surveys.show');
    Route::delete('/surveys/{id}', [App\Http\Controllers\AdminController::class, 'destroySurvey'])->name('surveys.destroy');

    // Folyamatban lévő kitöltések
    Route::get('/temporary-surveys', [App\Http\Controllers\AdminController::class, 'temporarySurveys'])->name('temporary-surveys');
    Route::get('/temporary-surveys/{id}', [App\Http\Controllers\AdminController::class, 'showTemporarySurvey'])->name('temporary-surveys.show');
    Route::delete('/temporary-surveys/{id}', [App\Http\Controllers\AdminController::class, 'destroyTemporarySurvey'])->name('temporary-surveys.destroy');

    // Felhasználók kezelése
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [App\Http\Controllers\AdminController::class, 'showUser'])->name('users.show');
    Route::delete('/users/{id}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::post('/users/{id}/make-admin', [App\Http\Controllers\AdminController::class, 'makeAdmin'])->name('users.make-admin');
    Route::post('/users/{id}/remove-admin', [App\Http\Controllers\AdminController::class, 'removeAdmin'])->name('users.remove-admin');

    // Statisztikák
    Route::get('/statistics', [App\Http\Controllers\AdminController::class, 'statistics'])->name('statistics');
    // Route::get('/admin/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');

    // Beállítások
    Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');




    // Intézmények kezelése
    Route::get('/institutions', [App\Http\Controllers\Admin\InstitutionsController::class, 'index'])->name('institutions.index');
    Route::get('/institutions/upload', [App\Http\Controllers\Admin\InstitutionsController::class, 'uploadForm'])->name('institutions.upload');
    Route::post('/institutions/import', [App\Http\Controllers\Admin\InstitutionsController::class, 'import'])->name('institutions.import');
    Route::get('/institutions/{id}/edit', [App\Http\Controllers\Admin\InstitutionsController::class, 'edit'])->name('institutions.edit');
    Route::put('/institutions/{id}', [App\Http\Controllers\Admin\InstitutionsController::class, 'update'])->name('institutions.update');
    Route::delete('/institutions/{id}', [App\Http\Controllers\Admin\InstitutionsController::class, 'destroy'])->name('institutions.destroy');
    Route::post('/institutions/generate-tracking', [App\Http\Controllers\Admin\InstitutionsController::class, 'generateTrackingCodes'])->name('institutions.generate-tracking');

    // Email küldés útvonalak
    Route::get('/emails', [App\Http\Controllers\Admin\EmailController::class, 'index'])->name('emails.index');
    Route::post('/emails/send-to-one/{id}', [App\Http\Controllers\Admin\EmailController::class, 'sendToOne'])->name('emails.send-to-one');
    Route::post('/emails/send-to-not-completed', [App\Http\Controllers\Admin\EmailController::class, 'sendToNotCompleted'])->name('emails.send-to-not-completed');


});
