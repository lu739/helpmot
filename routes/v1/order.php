<?php

use App\Enum\UserRole;
use App\Http\Middleware\CheckClientHasActiveOrderThisType;
use App\Http\Middleware\CheckDriverActivateAndNotBusy;
use App\Http\Middleware\CheckOrderBelongsToClient;
use App\Http\Middleware\CheckOrderBelongsToDriver;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Support\Facades\Route;


Route::get('/data-order-enums', \App\Http\Controllers\Api\Order\DataEnumsController::class)
    ->middleware('auth:sanctum')
    ->name('data_order_enums');


// ClientPart
// History client
Route::apiResource('/orders', \App\Http\Controllers\Api\Order\ClientPart\HistoryOrderController::class)
    ->only(['index', 'show'])
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::CLIENT->value,
    ]);

// Client actions
Route::post('/client/orders/{order}/cancel', \App\Http\Controllers\Api\Order\ClientPart\CancelledByClientOrderController::class)
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::CLIENT->value,
        CheckOrderBelongsToClient::class,
    ])
    ->name('client.orders.cancel');

Route::post('/client/orders/active/create', \App\Http\Controllers\Api\Order\ClientPart\CreateActiveOrderController::class)
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::CLIENT->value,
        CheckClientHasActiveOrderThisType::class,
    ])
    ->name('client.orders.active.create');


// DriverPart
// History driver
Route::apiResource('/driver/orders', \App\Http\Controllers\Api\Order\DriverPart\HistoryOrderController::class)
    ->names('driver.orders')
    ->only(['index', 'show'])
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::DRIVER->value
    ]);

// Active for driver
Route::apiResource('/driver/active/orders', \App\Http\Controllers\Api\Order\DriverPart\ActiveOrderController::class)
    ->names('driver.active.orders')
    ->only(['index', 'show'])
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::DRIVER->value,
        CheckDriverActivateAndNotBusy::class,
    ]);

// Drivers actions
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

Route::post('/driver/orders/{order}/cancel', \App\Http\Controllers\Api\Order\DriverPart\CancelledByDriverOrderController::class)
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::DRIVER->value,
        CheckOrderBelongsToDriver::class,
    ])
    ->name('driver.orders.cancel');

// Driver moving
Route::post('/driver/orders/{order}/update-location', \App\Http\Controllers\Api\Order\DriverPart\UpdateLocationByDriverOrderController::class)
    ->middleware([
        'auth:sanctum',
        CheckUserRole::class . ':' . UserRole::DRIVER->value,
        CheckOrderBelongsToDriver::class, // ToDo подумать, может стоит проверять статус заказа
    ])
    ->name('driver.orders.update_location');






