<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('/signup', [AuthController::class, 'signup']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

        Route::get('/profile', [ProfileController::class, 'show']);

        Route::put('/profile', [ProfileController::class, 'update']); 
});
