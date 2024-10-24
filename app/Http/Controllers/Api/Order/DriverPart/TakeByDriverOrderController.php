<?php

namespace App\Http\Controllers\Api\Order\DriverPart;

use App\Actions\Order\DriverPart\TakeByDriver\TakeOrderByDriverAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderActiveResource;
use App\Models\Order;
use App\States\Order\InProgressOrderState;

class TakeByDriverOrderController extends Controller
{
    public function __invoke(Order $order)
    {
        $order = (new TakeOrderByDriverAction())
            ->handle(
                $order,
                request()->user()->driver,
                new InProgressOrderState($order)
            );

        if ($order instanceof \Throwable) {
            return responseFailed(500, $order->getMessage());
        }

        return response()->json([
            'data' => OrderActiveResource::make($order->load('client')),
        ]);
    }
}
