<?php

namespace App\Actions\Order\DriverPart\TakeByDriver;

use App\Actions\Order\ChangeOrderStatusAction;
use App\Models\Driver;
use App\Models\Order;


class TakeOrderByDriverAction extends ChangeOrderStatusAction
{
    public function doUpdates(Order $order, Driver $driver): void
    {
        $order->update([
            'driver_id' => $driver->user->id,
            'date_start' => now(),
        ]);

        $driver->update([
            'is_busy' => true,
        ]);
    }
}
