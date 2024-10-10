<?php

namespace App\Actions\Order\TakeByDriver;

use App\Enum\OrderStatus;
use App\Models\Driver;
use App\Models\Order;


class TakeOrderByDriverAction {
    public function handle(Order $order, Driver $driver): Order
    {
        $order->update([
            'driver_id' => $driver->user->id,
            'status' => OrderStatus::IN_PROGRESS->value,
        ]);

        $driver->update([
            'is_busy' => true,
        ]);

        return $order;
    }
}
