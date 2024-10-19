<?php

namespace App\Http\Controllers\Api\Order\ClientPart;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderMinifiedResource;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Services\Exceptions\Order\OrderDoesNotBelongUserException;
use Illuminate\Http\Request;

class HistoryOrderController extends Controller
{
    public function index(Request $request)
    {
        $clientOrders = Order::query()
            ->history()
            ->where('client_id', $request->user()->id)
            ->get()
            ->sortByDesc('created_at');

        return response()->json([
            'data' => OrderMinifiedResource::collection($clientOrders),
        ]);
    }
    public function show(Request $request, Order $order)
    {
        if ($order->client_id !== $request->user()->id) {
            throw new OrderDoesNotBelongUserException();
        }

        return response()->json([
            'data' => OrderResource::make($order)->resolve(),
        ]);
    }
}
