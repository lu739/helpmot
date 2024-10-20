<?php

namespace App\Actions\Order\CancelledByClient;

use App\Actions\Order\ChangeOrderStatusAction;
use App\Models\Driver;
use App\Models\Order;


class CancelledOrderByClientAction extends ChangeOrderStatusAction
{
    public function doUpdates(Order $order, Driver $driver): void
    {
        $order->update([
            'date_start' => null,
            'driver_id' => null,
        ]);

        $driver->update([
            'is_busy' => false,
        ]);
    }
}
