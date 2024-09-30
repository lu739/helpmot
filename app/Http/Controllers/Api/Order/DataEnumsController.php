<?php

namespace App\Http\Controllers\Api\Order;

use App\Enum\OrderStatus;
use App\Enum\OrderType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataEnumsController extends Controller
{
    public function __invoke()
    {
        $orderTypes = [];
        $orderStatuses = [];

        foreach (OrderType::cases() as $orderType) {
            $orderTypes[$orderType->value] = $orderType->russian();
        }
        foreach (OrderStatus::cases() as $orderStatus) {
            $orderStatuses[$orderStatus->value] = $orderStatus->russian();
        }

        return response()->json([
            'types' => $orderTypes,
            'statuses' => $orderStatuses,
        ]);
    }
}
