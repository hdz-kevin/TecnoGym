<?php

namespace App\Enums;

enum MemberGender: string
{
    case MALE = 'M';
    case FEMALE = 'F';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    /**
     * Get the label for the member gender
     */
    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Masculino',
            self::FEMALE => 'Femenino',
        };
    }
}
