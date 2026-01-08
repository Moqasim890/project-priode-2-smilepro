<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AfspraakController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FactuurController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

// Guest routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    
    // Profile routes
    Route::get('/profiel', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profiel', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profiel/wachtwoord', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Praktijkmanagement routes (Admin)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    
    // Afspraken overzicht
    Route::get('/afspraken-overzicht', [DashboardController::class, 'afsprakenOverzicht'])->name('admin.afspraken.overzicht');
    
    // Omzet overzicht
    Route::get('/omzet-overzicht', [DashboardController::class, 'omzetOverzicht'])->name('admin.omzet.overzicht');
    
    // Omzet bekijken per periode (voor praktijkmanager)
    Route::get('/omzet-bekijken', [DashboardController::class, 'omzetBekijken'])->name('admin.omzet.bekijken');
    
    // Gebruikers beheer
    Route::get('/users',            [AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/patienten',        [AdminController::class, 'patienten'])->name('admin.patienten.index');
    Route::get('/patienten/create', [AdminController::class, 'createPatient'])->name('admin.patienten.create');
    Route::post('/patienten',       [AdminController::class, 'store'])->name('admin.patienten.store');
    Route::get('/feedback',         [AdminController::class, 'feedback'])->name('admin.feedback.index');
    Route::get('/berichten',        [AdminController::class, 'berichten'])->name('admin.berichten.index');
    Route::get('/berichten/create', [AdminController::class, 'create'])->name('admin.berichten.create');
    Route::post('/berichten',       [AdminController::class, 'store'])->name('admin.berichten.store');
});

Route::middleware(['auth', 'role:Patiënt'])->prefix('patient')->group(function () {
    Route::get('/berichten', [App\Http\Controllers\PatientController::class, 'getBerichtenById'])->name('Patient.berichten.index');

    Route::get('/berichten/create', [App\Http\Controllers\PatientController::class, 'create'])->name('Patient.berichten.create');
    Route::post('/berichten', [App\Http\Controllers\PatientController::class, 'store'])->name('Patient.berichten.store');
});

// Medewerker routes
Route::middleware(['auth'])->prefix('medewerker')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'medewerkerDashboard'])->name('medewerker.dashboard');
    
    // Facturen
    Route::get('/facturen', [FactuurController::class, 'index'])->name('medewerker.factuur.index');

    // Afspraken CRUD
    Route::get('/afspraken', [AfspraakController::class, 'index'])->name('medewerker.afspraken.index');
    Route::get('/afspraken/nieuw', [AfspraakController::class, 'create'])->name('medewerker.afspraken.create');
    Route::post('/afspraken', [AfspraakController::class, 'store'])->name('medewerker.afspraken.store');
    Route::get('/afspraken/{id}/bewerken', [AfspraakController::class, 'edit'])->name('medewerker.afspraken.edit');
    Route::put('/afspraken/{id}', [AfspraakController::class, 'update'])->name('medewerker.afspraken.update');
    Route::delete('/afspraken/{id}', [AfspraakController::class, 'destroy'])->name('medewerker.afspraken.destroy');
});