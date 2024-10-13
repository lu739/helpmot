<?php

namespace App\States\Order;

use App\Enum\OrderStatus;

class CreatedOrderState extends OrderState
{
    protected array $allowedTransitions = [
        ActiveOrderState::class
    ];

    public function canBeChanged(): bool
    {
        return true;
    }

    public function value(): string
    {
        return OrderStatus::CREATED->value;
    }
}
