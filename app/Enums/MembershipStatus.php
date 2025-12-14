<?php

namespace App\Enums;

enum MembershipStatus: int
{
    case ACTIVE = 1;
    case EXPIRED = 2;
    case NOT_STARTED = 3;

    /**
     * Get all enum values as an array
     *
     * @return array<int>
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * Get the label for the membership status
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Activa',
            self::EXPIRED => 'Vencida',
            self::NOT_STARTED => 'No iniciada',
        };
    }
}
