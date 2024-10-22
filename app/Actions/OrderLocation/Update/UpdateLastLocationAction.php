<?php

namespace App\Actions\OrderLocation\Update;

use App\Actions\OrderLocation\Update\Dto\UpdateOrderLocationDto;
use App\Models\OrderLocation;


class UpdateLastLocationAction {
    public function handle(UpdateOrderLocationDto $dto): OrderLocation
    {
        $orderLocation = OrderLocation::query()
            ->where('id', $dto->getId())
            ->first();

        $orderLocation->last_location = $dto->getLastLocation();
        $orderLocation->datetime = $dto->getDatetime();

        $orderLocation->save();

        return $orderLocation;
    }
}
