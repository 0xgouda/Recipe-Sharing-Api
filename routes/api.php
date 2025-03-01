<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::post('signup', [AuthController::class, 'signup']);

Route::post('login', [AuthController::class, 'login']);

Route::get('recipes/search', [RecipeController::class, 'search']);

Route::get('recipes/{id}', [RecipeController::class, 'show'])
    ->where('id', '[0-9]+');

Route::get('users/{user_id}/recipes', [RecipeController::class, 'showBy'])
    ->where('user_id', '[0-9]+');

Route::middleware('auth:sanctum')->group(function () {

        Route::get('logout', [AuthController::class, 'logout']);

        Route::get('profile', [ProfileController::class, 'show']);

        Route::put('profile', [ProfileController::class, 'update']); 

        Route::post('recipes', [RecipeController::class, 'store']);

        Route::post('recipes/{id}/rate', [RecipeController::class, 'rate'])
            ->where('id', '[0-9]+');

        Route::put('recipes/{id}/save', [ProfileController::class, 'save'])
            ->where('id', '[0-9]+');
        
        Route::put('recipes/{id}/unsave', [ProfileController::class, 'unsave'])
            ->where('id', '[0-9]+');
});