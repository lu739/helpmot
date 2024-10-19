<?php

use App\Enum\UserRole;
use App\Http\Middleware\CheckDriverActivateAndNotBusy;
use App\Http\Middleware\CheckOrderBelongsToDriver;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Support\Facades\Route;

Route::apiResource('/orders', \App\Http\Controllers\Api\Order\ClientPart\HistoryOrderController::class)
    ->only(['index', 'show'])
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::CLIENT->value,
    ]);


Route::post('/driver/orders/{order}/take', \App\Http\Controllers\Api\Order\DriverPart\TakeByDriverOrderController::class)
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::DRIVER->value,
        CheckDriverActivateAndNotBusy::class,
    ])
    ->name('driver.orders.take');

Route::post('/driver/orders/{order}/complete-successfully', \App\Http\Controllers\Api\Order\DriverPart\CompletedSuccessfullyByDriverOrderController::class)
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::DRIVER->value,
        CheckOrderBelongsToDriver::class,
    ])
    ->name('driver.orders.complete_successfully');


Route::apiResource('/driver/active/orders', \App\Http\Controllers\Api\Order\DriverPart\ActiveOrderController::class)
    ->names('driver.orders')
    ->only(['index', 'show'])
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::DRIVER->value,
        CheckDriverActivateAndNotBusy::class,
    ]);


Route::apiResource('/driver/orders', \App\Http\Controllers\Api\Order\DriverPart\HistoryOrderController::class)
    ->names('driver.orders')
    ->only(['index', 'show'])
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::DRIVER->value
    ]);


Route::get('/data-order-enums', \App\Http\Controllers\Api\Order\DataEnumsController::class)
    ->middleware('auth:sanctum')
    ->name('data_order_enums');
