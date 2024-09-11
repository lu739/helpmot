<?php

use Illuminate\Support\Facades\Route;

    Route::post('/onboarding', \App\Http\Controllers\Api\User\OnboardingUserController::class)
        ->name('onboarding');
    Route::post('/register', \App\Http\Controllers\Api\User\RegisterUserController::class)
        ->name('register');

    Route::post('/refresh-onboarding-user-code', \App\Http\Controllers\Api\User\RefreshCodeOnboardingUserController::class)
        ->name('refresh_onboarding_user_code');


