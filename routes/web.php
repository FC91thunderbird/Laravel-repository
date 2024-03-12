<?php
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('admin/{any?}', function () {
//     return view('admin.app');
// })->where('any', '.*');

Route::get('{any?}', function () {
    return view('welcome');
})->where('any', '.*');

// Route::get('{any}', function () {
//     return view('welcome');
// })->where('any', '.*');