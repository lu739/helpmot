<?php

namespace App\Http\Controllers\Api\Order\DriverPart;

use App\Actions\Order\TakeByDriver\TakeOrderByDriverAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderActiveResource;
use App\Models\Order;

class TakeByDriverOrderController extends Controller
{
    public function __invoke(Order $order)
    {
        try {
            $order = (new TakeOrderByDriverAction())->handle($order, request()->user()->driver);

            return response()->json([
                'data' => OrderActiveResource::make($order->load('client')),
            ]);
        } catch (\Throwable $e) {
            return responseFailed(500, $e->getMessage());
        }
    }
}
