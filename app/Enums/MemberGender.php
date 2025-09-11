<?php

namespace App\Enums;

enum MemberGender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
