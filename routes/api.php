<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProviderController;

// Rutas Públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas Protegidas
Route::middleware('firebase.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('providers', ProviderController::class);
});