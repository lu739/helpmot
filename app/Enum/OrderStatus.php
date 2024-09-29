<?php

namespace App\Enum;

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
}
