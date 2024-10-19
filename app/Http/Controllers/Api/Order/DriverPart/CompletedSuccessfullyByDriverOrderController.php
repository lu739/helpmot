<?php

namespace App\Http\Controllers\Api\Order\DriverPart;

use App\Actions\Order\TakeByDriver\CompletedSuccessfullyOrderByDriverAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderCompletedResource;
use App\Models\Order;

class CompletedSuccessfullyByDriverOrderController extends Controller
{
    public function __invoke(Order $order)
    {
        try {
            $order = (new CompletedSuccessfullyOrderByDriverAction())
                ->handle($order, request()->user()->driver);

            return response()->json([
                'data' => OrderCompletedResource::make($order->load('client')),
            ]);
        } catch (\Throwable $e) {
            return responseFailed(500, $e->getMessage());
        }
    }
}
