<?php

namespace App\Http\Controllers\Api\Order\ClientPart;

use App\Actions\Order\ClientPart\CancelledByClient\CancelledOrderByClientAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderCompletedResource;
use App\Models\Order;
use App\States\Order\ClientCancelOrderState;

class CancelledByClientOrderController extends Controller
{
    public function __invoke(Order $order)
    {
        try {
            $order = (new CancelledOrderByClientAction())
                ->handle(
                    $order,
                    request()->user()->driver,
                    new ClientCancelOrderState($order)
                );

            return response()->json([
                'data' => OrderCompletedResource::make($order->load('client')),
            ]);
        } catch (\Throwable $e) {
            return responseFailed(500, $e->getMessage());
        }
    }
}
