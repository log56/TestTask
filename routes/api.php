<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Unguarded path
Route::controller(AuthController::class)->group( function () {
    Route::post('/login', 'login');
});

//Guarded path
Route::middleware('auth:api')->group( function () {
    Route::controller(AuthController::class)->group( function () {
        Route::post('/logout', 'logout');
    });
});
