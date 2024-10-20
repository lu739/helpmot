<?php

namespace App\Actions\Order;

use App\Models\Driver;
use App\Models\Order;
use App\States\Order\OrderState;
use Illuminate\Support\Facades\DB;

abstract class ChangeOrderStatusAction
{
    abstract public function doUpdates(Order $order, Driver $driver): void;

    public function handle(Order $order, Driver $driver, OrderState $newStatusState): Order|\Throwable
    {
        try {
            DB::beginTransaction();
            $order->statusState->transitionTo($newStatusState);

            $this->doUpdates($order, $driver);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return $e;
        }

        return $order;
    }
}
