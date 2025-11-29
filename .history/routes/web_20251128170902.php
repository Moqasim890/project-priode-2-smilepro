<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// Guest routes (alleen voor niet-ingelogde gebruikers)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes (alleen voor ingelogde gebruikers)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

// Admin routes (alleen voor admin gebruikers)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// Medewerker routes (voor admin en medewerker)
Route::middleware(['auth', 'role:admin,medewerker'])->prefix('medewerker')->name('medewerker.')->group(function () {
    Route::get('/dashboard', function () {
        return view('medewerker.dashboard');
    })->name('dashboard');
});

