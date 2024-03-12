<?php

use Illuminate\Support\Facades\Route;


Route::get('{any}', function () {
    return view('welcome');
})->where('any', '.*');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Route::view('/{any}', 'dashboard')
//     ->middleware(['auth'])
//     ->where('any', '.*');
