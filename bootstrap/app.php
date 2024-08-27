<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e) {
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return responseFailed(401, __('exceptions.no_auth'));
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                if ($e->getPrevious() instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return responseFailed(404, __('exceptions.model_not_found'));
                }
                return responseFailed(404, __('exceptions.route_not_found'));
            }
            if ($e instanceof \App\Services\Exceptions\Driver\DriverNotActiveException) {
                return responseFailed(404, __('exceptions.driver_not_active'));
            }
        });
    })->create();
