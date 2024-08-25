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
}
