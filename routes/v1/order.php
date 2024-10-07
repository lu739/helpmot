<?php

use App\Enum\UserRole;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Support\Facades\Route;

Route::apiResource('/orders', \App\Http\Controllers\Api\Order\OrderController::class)
    ->middleware('auth:sanctum');

Route::get('/driver/orders/active', [\App\Http\Controllers\Api\Order\Driver\OrderController::class, 'active'])
    ->middleware(['auth:sanctum', CheckUserRole::class . ':' . UserRole::DRIVER->value])
    ->name('driver.orders.active');
Route::apiResource('/driver/orders', \App\Http\Controllers\Api\Order\Driver\OrderController::class)
    ->only(['index', 'show'])
    ->middleware(['auth:sanctum', CheckUserRole::class . ':' . UserRole::DRIVER->value]);

Route::get('/data-order-enums', \App\Http\Controllers\Api\Order\DataEnumsController::class)
    ->middleware('auth:sanctum')
    ->name('data_order_enums');
