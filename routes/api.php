<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::post('signup', [AuthController::class, 'signup']);

    Route::post('login', [AuthController::class, 'login']);

    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

        Route::get('profile', [ProfileController::class, 'show']);

        Route::put('profile', [ProfileController::class, 'update']); 

        Route::post('recipes', [RecipeController::class, 'store']);

        Route::get('recipes/search', [RecipeController::class, 'search']);

        Route::get('recipes/{id}', [RecipeController::class, 'show'])
            ->where('id', '[0-9]+');
        
        Route::post('recipes/{id}/rate', [RecipeController::class, 'rate'])
            ->where('id', '[0-9]+');
});