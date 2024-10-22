<?php

namespace App\Actions\OrderLocation\Create;

use App\Actions\OrderLocation\Create\Dto\CreateOrderLocationDto;
use App\Models\OrderLocation;


class CreateLastLocationAction {
    public function handle(CreateOrderLocationDto $dto): OrderLocation
    {
        $orderLocation = new OrderLocation();
        $orderLocation->order_id = $dto->getOrderId();
        $orderLocation->driver_id = $dto->getDriverId();
        $orderLocation->last_location = $dto->getLastLocation();
        $orderLocation->start_location = $dto->getStartLocation();
        $orderLocation->datetime = $dto->getDatetime();

        $orderLocation->save();

        return $orderLocation;
    }
}
