<?php

namespace App\Enum;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
    case DRIVER = 'driver';

    public function russian(): string
    {
        return match ($this) {
            self::ADMIN => 'Администратор',
            self::CLIENT => 'Клиент',
            self::DRIVER => 'Водитель',
        };
    }
}
