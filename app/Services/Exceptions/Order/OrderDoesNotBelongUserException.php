<?php

namespace App\Services\Exceptions\Order;
class OrderDoesNotBelongUserException extends \Exception
{
    public function __construct($message = null, $code = 403, ?\Throwable $previous = null)
    {
        parent::__construct($message ?? __('exceptions.order_does_not_belong_to_user'), $code, $previous);
    }
}
