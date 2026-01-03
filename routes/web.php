<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AfsprakenController;
use App\Http\Controllers\Managementdashboard;
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

// Praktijkmanagement routes
Route::middleware(['auth', 'role:Praktijkmanagement'])->prefix('management')->group(function () {
    Route::get('/management/shboard', [Managementdashboard::class, 'index'])->name('adminn.dashboard');

        Route::get('/dashboard', function () { 
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/patienten', [App\Http\Controllers\AdminController::class, 'patienten'])->name('admin.patienten.index');
    Route::get('/feedback', [App\Http\Controllers\AdminController::class, 'feedback'])->name('admin.feedback.index');
    Route::get('/berichten', [App\Http\Controllers\AdminController::class, 'berichten'])->name('admin.berichten.index');
    Route::get('/berichten/create', [App\Http\Controllers\AdminController::class, 'create'])->name('admin.berichten.create');
    Route::post('/berichten', [App\Http\Controllers\AdminController::class, 'store'])->name('admin.berichten.store');
    Route::get('/facturen', [App\Http\Controllers\FactuurController::class, 'index'])->name('medewerker.factuur.index');
    
    // Afspraken routes
    Route::get('/afspraken', [AfsprakenController::class, 'index'])->name('afspraken.index');
});

Route::middleware(['auth', 'role:Patiënt'])->prefix('patient')->group(function () {
    Route::get('/berichten', [App\Http\Controllers\PatientController::class, 'getBerichtenById'])->name('Patient.berichten.index');

    Route::get('/berichten/create', [App\Http\Controllers\PatientController::class, 'create'])->name('Patient.berichten.create');
    Route::post('/berichten', [App\Http\Controllers\PatientController::class, 'store'])->name('Patient.berichten.store');
});

// Medewerker routes (accessible by Praktijkmanagement, Tandarts, Mondhygiënist, and Assistent)
Route::middleware(['auth', 'role:Praktijkmanagement,Tandarts,Mondhygiënist,Assistent'])->prefix('medewerker')->group(function () {
    Route::get('/dashboard', function () {
        return view('medewerker.dashboard');
    })->name('medewerker.dashboard');



    Route::get('/facturen', [App\Http\Controllers\FactuurController::class, 'index'])->name('medewerker.factuur.index');
});