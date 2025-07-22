<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Unguarded path
Route::controller(AuthController::class)->group( function () {
    Route::post('/login', 'login');
});
Route::controller(UserController::class)->group( function () {
    Route::post('/register', 'store');
});

//Guarded path
Route::middleware('auth:api')->group( function () {
    Route::controller(AuthController::class)->group( function () {
        Route::post('/logout', 'logout');
    });
});
