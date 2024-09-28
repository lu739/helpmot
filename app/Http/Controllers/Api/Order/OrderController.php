<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $userOrders = $request->user()->orders->sortByDesc('created_at');

        return response()->json([
            'data' => OrderResource::collection($userOrders),
        ]);
    }
}
