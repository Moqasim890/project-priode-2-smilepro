<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AfsprakenController;
use App\Http\Controllers\Managementdashboard;

Route::get('/', [Managementdashboard::class, 'index'])->name('dashboard');

Route::get('/afspraken', [AfsprakenController::class, 'index'])->name('afspraken.index');