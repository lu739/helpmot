<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderMinifiedResource;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Services\Exceptions\Order\OrderDoesNotBelongClientException;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $userOrders = $request->user()->orders->sortByDesc('created_at');

        return response()->json([
            'data' => OrderMinifiedResource::collection($userOrders),
        ]);
    }
    public function show(Request $request, Order $order)
    {
        if ($order->client_id !== $request->user()->id) {
            throw new OrderDoesNotBelongClientException();
        }

        return response()->json([
            'data' => OrderResource::make($order)->resolve(),
        ]);
    }
}
