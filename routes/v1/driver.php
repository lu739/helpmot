<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('/drivers', \App\Http\Controllers\Api\Driver\DriverController::class);
