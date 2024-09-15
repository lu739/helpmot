<?php

use Illuminate\Support\Facades\Route;

    Route::middleware('throttle:api')->group(function () {
        Route::post('/login', \App\Http\Controllers\Api\User\LoginUserController::class)
            ->name('login');
    });

    Route::post('/logout', \App\Http\Controllers\Api\User\LogoutUserController::class)
        ->middleware('auth:sanctum')
        ->name('logout');

    Route::post('/refresh-tokens', \App\Http\Controllers\Api\User\RefreshTokensUserController::class)
        ->middleware('auth:sanctum')
        ->name('refresh_tokens');

    Route::post('/refresh-user-code', \App\Http\Controllers\Api\User\RefreshCodeUserController::class)
        ->name('refresh_user_code');

    Route::post('/forget-password', \App\Http\Controllers\Api\User\ForgetPasswordUserController::class)
        ->name('forget_password');
    Route::post('/refresh-password', \App\Http\Controllers\Api\User\RefreshPasswordUserController::class)
        ->name('refresh_password');

