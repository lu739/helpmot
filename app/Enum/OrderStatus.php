<?php

namespace App\Enum;

use App\Models\Order;
use App\States\Order\ActiveOrderState;
use App\States\Order\ClientCancelOrderState;
use App\States\Order\CompletedSuccessfullyOrderState;
use App\States\Order\CreatedOrderState;
use App\States\Order\DriverCancelOrderState;
use App\States\Order\InProgressOrderState;
use App\States\Order\OrderState;
use App\States\Order\TimeExpiredOrderState;

enum OrderStatus: string
{
    case CREATED = 'created';
    case ACTIVE = 'active';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED_SUCCESSFULLY = 'completed_successfully';
    case TIME_EXPIRED = 'time_expired';
    case DRIVER_CANCEL = 'driver_cancel';
    case CLIENT_CANCEL = 'client_cancel';

    public function russian(): string
    {
        return match ($this) {
            self::CREATED => 'Создан',
            self::ACTIVE => 'Активен',
            self::IN_PROGRESS => 'В процессе',
            self::COMPLETED_SUCCESSFULLY => 'Успешно завершен',
            self::TIME_EXPIRED => 'Время ожидания истекло',
            self::DRIVER_CANCEL => 'Отменен водителем',
            self::CLIENT_CANCEL => 'Отменен клиентом',
        };
    }

    public function createState(Order $order): OrderState
    {
        return match ($this) {
            self::CREATED => new CreatedOrderState($order),
            self::ACTIVE => new ActiveOrderState($order),
            self::IN_PROGRESS => new InProgressOrderState($order),
            self::COMPLETED_SUCCESSFULLY => new CompletedSuccessfullyOrderState($order),
            self::TIME_EXPIRED => new TimeExpiredOrderState($order),
            self::DRIVER_CANCEL => new DriverCancelOrderState($order),
            self::CLIENT_CANCEL => new ClientCancelOrderState($order),
        };
    }
}
