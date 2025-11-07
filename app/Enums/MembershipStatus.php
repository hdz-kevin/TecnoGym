<?php

namespace App\Enums;

enum MembershipStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    /** Pending is when a membership does not have any payment */
    case PENDING = 'pending';

    /**
     * Get all enum values as an array
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
            self::PENDING => 'Pendiente',
        };
    }
}
