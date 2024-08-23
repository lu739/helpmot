<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/')->group(function () {
    require __DIR__ . '/v1/driver.php';
    require __DIR__ . '/v1/user.php';
});
