<?php

namespace App\Http\Controllers\Api\Order\DriverPart;

use App\Actions\Order\TakeByDriver\TakeOrderByDriverAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderActiveResource;
use App\Http\Resources\Order\OrderMinifiedResource;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Services\Exceptions\Order\OrderDoesNotBelongUserException;

class OrderController extends Controller
{
    public function index()
    {
        $driverOrders = Order::query()
            ->where('driver_id', request()->user()->id)
            ->get();

        return response()->json([
            'data' => OrderMinifiedResource::collection($driverOrders),
        ]);
    }

    public function show(Order $order)
    {
        if (is_null($order->driver) || $order->driver->id !== request()->user()->id) {
            throw new OrderDoesNotBelongUserException();
        }

        return response()->json([
            'data' => OrderResource::make($order)->resolve(),
        ]);
    }

    public function takeByDriver(Order $order)
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
