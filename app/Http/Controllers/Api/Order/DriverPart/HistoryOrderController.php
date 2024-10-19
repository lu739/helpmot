<?php

namespace App\Http\Controllers\Api\Order\DriverPart;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderMinifiedResource;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Services\Exceptions\Order\OrderDoesNotBelongUserException;

class HistoryOrderController extends Controller
{
    public function index()
    {
        $driverOrders = Order::query()
            ->history()
            ->where('driver_id', request()->user()->id)
            ->get()
            ->sortByDesc('created_at');

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
}
