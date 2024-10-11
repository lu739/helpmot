<?php

use App\Enum\UserRole;
use App\Http\Middleware\CheckDriverActivateAndNotBusy;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Support\Facades\Route;

Route::apiResource('/orders', \App\Http\Controllers\Api\Order\ClientPart\OrderController::class)
    ->only(['index', 'show'])
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::CLIENT->value,
    ]);


Route::post('/driver/orders/{order}/take', [\App\Http\Controllers\Api\Order\DriverPart\OrderController::class, 'takeByDriver'])
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::DRIVER->value,
        CheckDriverActivateAndNotBusy::class,
    ])
    ->name('driver.orders.take');


Route::apiResource('/driver/active/orders', \App\Http\Controllers\Api\Order\DriverPart\ActiveOrderController::class)
    ->names('driver.orders')
    ->only(['index', 'show'])
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::DRIVER->value,
        CheckDriverActivateAndNotBusy::class,
    ]);


Route::apiResource('/driver/orders', \App\Http\Controllers\Api\Order\DriverPart\OrderController::class)
    ->names('driver.orders')
    ->only(['index', 'show'])
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::DRIVER->value
    ]);


Route::get('/data-order-enums', \App\Http\Controllers\Api\Order\DataEnumsController::class)
    ->middleware('auth:sanctum')
    ->name('data_order_enums');
