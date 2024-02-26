<?php

namespace App\Enums;

enum NewsStatusEnum: string
{
    case ACTIVE = 'active';
    case DRAFT = 'draft';
    case ARCHIVE = 'archive';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}