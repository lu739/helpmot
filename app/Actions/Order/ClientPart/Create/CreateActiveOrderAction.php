<?php

namespace App\Actions\Order\ClientPart\Create;

use App\Actions\Order\ClientPart\Create\Dto\CreateActiveOrderDto;
use App\Models\Order;

class CreateActiveOrderAction
{
    public function handle(CreateActiveOrderDto $dto): Order
    {
        $order = new Order();
        $order->client_id = $dto->getClientId();
        $order->type = $dto->getType()->value;
        $order->location_start = $dto->getLocationStart();
        $order->status = $dto->getStatus()->value;
        $order->client_comment = $dto->getClientComment();
        $order->save();

        return $order;
    }
}
