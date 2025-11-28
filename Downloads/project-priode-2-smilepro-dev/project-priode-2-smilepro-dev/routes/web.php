<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});



Route::get('/afspraken', function () {
    return view('afspraken.index');
})->name('afspraken.index');