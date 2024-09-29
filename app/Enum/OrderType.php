<?php

namespace App\Enum;

enum OrderType: string
{
    case TOW_TRUCK = 'tow_truck';
    case PURCHASE = 'purchase';
    case REGISTRATION = 'registration';

    public function russian(): string
    {
        return match ($this) {
            self::TOW_TRUCK => 'Эвакуатор',
            self::PURCHASE => 'Покупка',
            self::REGISTRATION => 'Регистрационные действия',
        };
    }
}
