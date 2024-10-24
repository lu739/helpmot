<?php

namespace App\Actions\Order\DriverPart\CompletedSuccessfullyByDriver;

use App\Actions\Order\ChangeOrderStatusAction;
use App\Models\Driver;
use App\Models\Order;


class CompletedSuccessfullyOrderByDriverAction extends ChangeOrderStatusAction
{
    public function doUpdates(Order $order, Driver $driver): void
    {
        $order->update([
            'date_end' => now(),
        ]);

        $driver->update([
            'is_busy' => false,
        ]);
    }
}
