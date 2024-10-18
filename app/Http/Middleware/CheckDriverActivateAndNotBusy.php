<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDriverActivateAndNotBusy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (request()->user()->driver->isBusy()) {
            return responseFailed(403, __('exceptions.driver_has_active_order'));
        }
        if (!request()->user()->driver->isActivate()) {
            return responseFailed(403, __('exceptions.driver_not_activate'));
        }

        return $next($request);
    }
}
