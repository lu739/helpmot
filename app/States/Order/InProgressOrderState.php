<?php

namespace App\States\Order;

use App\Enum\OrderStatus;

class InProgressOrderState extends OrderState
{
    protected array $allowedTransitions = [
        CompletedSuccessfullyOrderState::class,
        DriverCancelOrderState::class,
        ClientCancelOrderState::class,
    ];

    public function canBeChanged(): bool
    {
        return true;
    }

    public function value(): string
    {
        return OrderStatus::IN_PROGRESS->value;
    }
}
