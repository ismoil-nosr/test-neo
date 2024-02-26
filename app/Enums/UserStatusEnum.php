<?php

namespace App\Enums;

enum UserStatusEnum: string
{
    case ACTIVE = 'active';
    case BANNED = 'banned';
    case PENDING = 'pending';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
