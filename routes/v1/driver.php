<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('/drivers', \App\Http\Controllers\Api\Driver\DriverController::class);

Route::post('/drivers/activate', \App\Http\Controllers\Api\Driver\ActivateDriverController::class)
    ->middleware(['auth:sanctum', \App\Http\Middleware\CheckDriverNotActivate::class])
    ->name('driver.activate');
Route::post('/drivers/deactivate', \App\Http\Controllers\Api\Driver\DeactivateDriverController::class)
    ->middleware(['auth:sanctum', \App\Http\Middleware\CheckDriverActivateAndNotBusy::class])
    ->name('driver.deactivate');
