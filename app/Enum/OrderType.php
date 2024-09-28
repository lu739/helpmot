<?php

namespace App\Enum;

enum OrderType: string
{
    case TOW_TRUCK = 'tow_truck';
    case PURCHASE = 'purchase';
    case REGISTRATION = 'registration';
}
