<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Api\User\UserController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    // Route::get('/register', 'register')->name('register');
});
