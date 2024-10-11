<?php

namespace App\Http\Controllers\Api\Order\DriverPart;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderActiveMinifiedResource;
use App\Http\Resources\Order\OrderActiveResource;
use App\Models\Order;
use Illuminate\Http\Request;

class ActiveOrderController extends Controller
{
    public function index()
    {
        $activeOrders = Order::query()->active()->get();

        return response()->json([
            'data' => OrderActiveMinifiedResource::collection($activeOrders),
        ]);
    }

    public function show(Order $order)
    {
        return response()->json([
            'data' => OrderActiveResource::make($order->load('client')),
        ]);
    }
}
