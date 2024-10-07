<?php

use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
        Route::post('/onboarding', \App\Http\Controllers\Api\User\Onboarding\OnboardingUserController::class)
            ->name('onboarding');
    });

    Route::post('/register', \App\Http\Controllers\Api\User\RegisterUserController::class)
        ->name('register');

    Route::post('/refresh-onboarding-user-code', \App\Http\Controllers\Api\User\Onboarding\RefreshCodeOnboardingUserController::class)
        ->name('refresh_onboarding_user_code');


