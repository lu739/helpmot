<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('/orders', \App\Http\Controllers\Api\Order\OrderController::class)
    ->middleware('auth:sanctum');


Route::get('/data-order-enums', \App\Http\Controllers\Api\Order\DataEnumsController::class)
    ->middleware('auth:sanctum')
    ->name('data_order_enums');
