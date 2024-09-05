<?php

use Illuminate\Support\Facades\Route;

    Route::post('/onboarding', \App\Http\Controllers\Api\User\OnboardingUserController::class)
        ->name('onboarding');
    Route::post('/register', \App\Http\Controllers\Api\User\RegisterUserController::class)
        ->name('register');

    Route::post('/login', \App\Http\Controllers\Api\User\LoginUserController::class)
        ->name('login');
    Route::post('/logout', \App\Http\Controllers\Api\User\LogoutUserController::class)
        ->middleware('auth:sanctum')
        ->name('logout');

    Route::post('/refresh-tokens', \App\Http\Controllers\Api\User\RefreshTokensUserController::class)
        ->middleware('auth:sanctum')
        ->name('refresh_tokens');

    Route::post('/forget-password', \App\Http\Controllers\Api\User\ForgetPasswordUserController::class)
        ->name('forget_password');
    Route::post('/refresh-password', \App\Http\Controllers\Api\User\RefreshPasswordUserController::class)
        ->name('refresh_password');

