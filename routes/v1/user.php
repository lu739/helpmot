<?php

use Illuminate\Support\Facades\Route;

    Route::post('/onboarding', \App\Http\Controllers\Api\User\OnboardingUserController::class)
        ->name('onboarding');
    Route::post('/register', \App\Http\Controllers\Api\User\RegisterUserController::class)
        ->name('register');

    Route::post('/login', \App\Http\Controllers\Api\User\LoginUserController::class)
        ->name('login');

    Route::post('/refresh-token', \App\Http\Controllers\Api\User\RefreshTofensUserController::class)
        ->middleware('auth:sanctum')
        ->name('refresh');

