<?php

namespace App\Services\Exceptions\Driver;
class DriverNotActiveException extends \Exception
{
    public function __construct($message = null, $code = 403, ?\Throwable $previous = null)
    {
        parent::__construct($message ?? __('exceptions.driver_not_activate'), $code, $previous);
    }
}
