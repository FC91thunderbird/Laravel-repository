<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/user', [AuthController::class, 'getUserDetails']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route::group(['prefix' => 'users'], function () {
    //     Route::get('/', [UserController::class, 'index']);
    //     Route::post('/', [UserController::class, 'store']);
    //     Route::get('/{id}/edit', [UserController::class, 'edit']);
    //     Route::put('/{id}/update', [UserController::class, 'update']);
    //     Route::delete('/{id}/delete', [UserController::class, 'destroy']);


    //     // Route::resource('/', UserController::class);
    //     // Route::get('/{id}/show', [UserController::class, 'show']);
    //     // Route::get('/export', [UserController::class, 'export']);
    // });
    
    Route::get('/users/getRoles', [UserController::class, 'getRoles']);
    Route::resource('users', UserController::class);

    Route::group(['prefix' => 'admin'], function () {
        Route::apiResource('/category', CategoryController::class);
    });

});
