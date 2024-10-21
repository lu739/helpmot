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

    public function transitionTo(OrderState $state): Order|\Throwable
    {
        if (!$this->canBeChanged()) {
            throw new \Exception(__('exceptions.order_status_cant_be_changed'));
        }

        if (!in_array(get_class($state), $this->allowedTransitions)) {
            throw new \Exception(__('exceptions.order_status_transaction_wrong'));
        }

        $this->order->update([
            'status' => $state->value()
        ]);

        return $this->order;
    }
}
