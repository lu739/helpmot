<?php

namespace App\Http\Middleware;

use App\Enum\OrderStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckClientHasActiveOrderThisType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $type = $request->input('type');
        $hasOrder = $user->orders()
            ->where('type', $type)
            ->where('status', OrderStatus::ACTIVE->value)
            ->exists();

        if ($hasOrder) {
            return responseFailed(403, __('exceptions.client_already_has_active_order_this_type'));
        }

        return $next($request);
    }
}
