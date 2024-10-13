<?php

namespace App\States\Order;

use App\Models\Order;

abstract class OrderState
{

    protected array $allowedTransitions = [];

    public function __construct(protected Order $order)
    {
    }

    abstract public function canBeChanged(): bool;
    abstract public function value(): string;

    public function transitionTo(OrderState $state)
    {
        if (!$this->canBeChanged()) {
            return 'Order can\'t be changed';
        }

        if (!in_array(get_class($state), $this->allowedTransitions)) {
            return 'This transition is not allowed';
        }

        $this->order->update([
            'status' => $state->value()
        ]);

        return $this->order;
    }
}
