<?php

namespace App\Actions\Order\TakeByDriver;

use App\Models\Driver;
use App\Models\Order;
use App\States\Order\CompletedSuccessfullyOrderState;
use Illuminate\Support\Facades\DB;


class CompletedSuccessfullyOrderByDriverAction {
    public function handle(Order $order, Driver $driver): Order|\Throwable
    {
        try {
            DB::beginTransaction();
                $order->statusState->transitionTo(new CompletedSuccessfullyOrderState($order));

                $order->update([
                    'date_end' => now(),
                ]);

                $driver->update([
                    'is_busy' => false,
                ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }

        return $order;
    }
}
