<?php

namespace App\Http\Controllers\Api\Order\Driver;

use App\Actions\Order\TakeByDriver\TakeOrderByDriverAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderActiveMinifiedResource;
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

    public function activeIndex()
    {
        if (request()->user()->driver->isBusy()) {
            return responseFailed(403, __('exceptions.driver_has_active_order'));
        }

        $activeOrders = Order::query()->active()->get();

        return response()->json([
            'data' => OrderActiveMinifiedResource::collection($activeOrders),
        ]);
    }

    public function activeShow(Order $order)
    {
        if (request()->user()->driver->isBusy()) {
            return responseFailed(403, __('exceptions.driver_has_active_order'));
        }

        return response()->json([
            'data' => OrderActiveResource::make($order->load('client')),
        ]);
    }

    public function takeByDriver(Order $order)
    {
        // ToDo методы, относящиеся к активациям/активным заказам вынести в отдельный контроллер
        // ToDo сделать мидлвар для проверки прав водителя (он должен быть активированным и не занятым)
        if (request()->user()->driver->isBusy()) {
            return responseFailed(403, __('exceptions.driver_has_active_order'));
        }
        if (!request()->user()->driver->isActivate()) {
            return responseFailed(403, __('exceptions.driver_not_active'));
        }
        // ToDo сделать смену статуса заказа с использованием красивого паттерна

        $order = (new TakeOrderByDriverAction())
            ->handle($order, request()->user()->driver);

        return response()->json([
            'data' => OrderActiveResource::make($order->load('client')),
        ]);
    }
}
