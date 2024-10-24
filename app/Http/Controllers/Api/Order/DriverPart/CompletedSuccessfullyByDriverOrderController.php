<?php

namespace App\Http\Controllers\Api\Order\DriverPart;

use App\Actions\Order\DriverPart\CompletedSuccessfullyByDriver\CompletedSuccessfullyOrderByDriverAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderCompletedResource;
use App\Models\Order;
use App\States\Order\CompletedSuccessfullyOrderState;

class CompletedSuccessfullyByDriverOrderController extends Controller
{
    public function __invoke(Order $order)
    {
        try {
            $order = (new CompletedSuccessfullyOrderByDriverAction())
                ->handle(
                    $order,
                    request()->user()->driver,
                    new CompletedSuccessfullyOrderState($order)
                );

            return response()->json([
                'data' => OrderCompletedResource::make($order->load('client')),
            ]);
        } catch (\Throwable $e) {
            return responseFailed(500, $e->getMessage());
        }
    }
}
