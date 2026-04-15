<?php

namespace App\Enums;

enum MembershipStatus: int
{
    case ACTIVE = 1;
    case EXPIRED = 2;

    /**
     * Get the label for the membership status
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Activa',
            self::EXPIRED => 'Vencida',
        };
    }
}
