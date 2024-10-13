<?php

namespace App\States\Order;

use App\Enum\OrderStatus;

class ActiveOrderState extends OrderState
{
    protected array $allowedTransitions = [
        CreatedOrderState::class,
        TimeExpiredOrderState::class,
        InProgressOrderState::class
    ];

    public function canBeChanged(): bool
    {
        return true;
    }

    public function value(): string
    {
        return OrderStatus::ACTIVE->value;
    }
}
