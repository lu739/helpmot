<?php

namespace App\States\Order;

use App\Enum\OrderStatus;

class CompletedSuccessfullyOrderState extends OrderState
{
    protected array $allowedTransitions = [
    ];

    public function canBeChanged(): bool
    {
        return false;
    }

    public function value(): string
    {
        return OrderStatus::COMPLETED_SUCCESSFULLY->value;
    }
}
