<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
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

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users.index');
    
    // Facturen routes
    Route::get('/facturen', [App\Http\Controllers\FactuurController::class, 'index'])->name('admin.facturen.index');
    Route::get('/facturen/create', [App\Http\Controllers\FactuurController::class, 'create'])->name('admin.facturen.create');
    Route::post('/facturen', [App\Http\Controllers\FactuurController::class, 'store'])->name('admin.facturen.store');
    Route::get('/facturen/{id}/edit', [App\Http\Controllers\FactuurController::class, 'edit'])->name('admin.facturen.edit');
    Route::put('/facturen/{id}', [App\Http\Controllers\FactuurController::class, 'update'])->name('admin.facturen.update');
    Route::delete('/facturen/{id}', [App\Http\Controllers\FactuurController::class, 'destroy'])->name('admin.facturen.destroy');
    Route::get('/facturen/behandelingen/{patientId}', [App\Http\Controllers\FactuurController::class, 'getBehandelingen']);
});

// Medewerker routes (accessible by admin and medewerker)
Route::middleware(['auth', 'role:admin,medewerker'])->prefix('medewerker')->group(function () {
    Route::get('/dashboard', function () {
        return view('medewerker.dashboard');
    })->name('medewerker.dashboard');
});


