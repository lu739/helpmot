<?php

namespace App\Http\Controllers\Api\Order\DriverPart;

use App\Actions\Order\CancelledByDriver\CancelledOrderByDriverAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderCompletedResource;
use App\Models\Order;
use App\States\Order\DriverCancelOrderState;

class CancelledByDriverOrderController extends Controller
{
    public function __invoke(Order $order)
    {
        try {
            $order = (new CancelledOrderByDriverAction())
                ->handle(
                    $order,
                    request()->user()->driver,
                    new DriverCancelOrderState($order)
                );

            return response()->json([
                'data' => OrderCompletedResource::make($order->load('client')),
            ]);
        } catch (\Throwable $e) {
            return responseFailed(500, $e->getMessage());
        }
    }
}
