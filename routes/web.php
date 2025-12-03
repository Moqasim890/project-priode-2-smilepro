<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
<<<<<<< HEAD
use App\Http\Controllers\AfsprakenController;
use App\Http\Controllers\Managementdashboard;
=======
use App\Http\Controllers\MedewerkerOverzichtController;
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');

// Guest routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
<<<<<<< HEAD
    
=======

>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

<<<<<<< HEAD
// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    
    // Profile routes
    Route::get('/profiel', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profiel', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profiel/wachtwoord', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Praktijkmanagement routes
// Route::middleware(['auth', 'role:Praktijkmanagement'])->prefix('management')->group(function () {
    Route::get('/dashboard', [Managementdashboard::class, 'index'])->name('admin.dashboard');

    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/patienten', [App\Http\Controllers\AdminController::class, 'patienten'])->name('admin.patienten.index');
    
    // Afspraken routes
    Route::get('/afspraken', [AfsprakenController::class, 'index'])->name('afspraken.index');
// });

// Medewerker routes (accessible by Praktijkmanagement, Tandarts, Mondhygiënist, and Assistent)
Route::middleware(['auth', 'role:Praktijkmanagement,Tandarts,Mondhygiënist,Assistent'])->prefix('medewerker')->group(function () {
    Route::get('/dashboard', function () {
        return view('medewerker.dashboard');
    })->name('medewerker.dashboard');

    Route::get('/facturen', [App\Http\Controllers\FactuurController::class, 'index'])->name('medewerker.factuur.index');
=======
// Authenticated routes (only accessible when logged in)
Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profiel', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profiel', [ProfileController::class, 'update']);

    // Logout route
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Praktijkmanagement routes
    Route::middleware(['auth', 'role:Praktijkmanagement'])->prefix('management')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users.index');

        Route::get('/medewerkers', [MedewerkerOverzichtController::class, 'index'])->name('management.medewerkers.index');
    });

    // Medewerker routes (accessible by Praktijkmanagement, Tandarts, Mondhygiënist, and Assistent)
    Route::middleware(['auth', 'role:Praktijkmanagement,Tandarts,Mondhygiënist,Assistent'])->prefix('medewerker')->group(function () {
        Route::get('/dashboard', function () {
            return view('medewerker.dashboard');
        })->name('medewerker.dashboard');

        Route::get('/facturen', [App\Http\Controllers\FactuurController::class, 'index'])->name('medewerker.factuur.index');
    });
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
});