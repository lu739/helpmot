<?php

namespace App\Actions\Order\TakeByDriver;

use App\Enum\OrderStatus;
use App\Models\Driver;
use App\Models\Order;
use App\States\Order\InProgressOrderState;
use Illuminate\Support\Facades\DB;


class TakeOrderByDriverAction {
    public function handle(Order $order, Driver $driver): Order|\Throwable
    {
        try {
            DB::beginTransaction();
                $order->statusState->transitionTo(new InProgressOrderState($order));

                $order->update([
                    'driver_id' => $driver->user->id,
                    'date_start' => now(),
                    // 'status' => OrderStatus::IN_PROGRESS->value
                    // 'status' => OrderStatus::from($newStatusOrder->status)->value
                ]);

                $driver->update([
                    'is_busy' => true,
                ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }

        return $order;
        // return $newStatusOrder;
    }
}
