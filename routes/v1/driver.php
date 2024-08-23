<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('/driver', \App\Http\Controllers\Api\Driver\DriverController::class);
