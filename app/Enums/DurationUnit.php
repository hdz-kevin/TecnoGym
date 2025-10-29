<?php

namespace App\Enums;

enum DurationUnit: string
{
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';

    /**
     * Get all enum values as an array
     */
    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    /**
     * Get the label for the duration unit, singular or plural based on quantity
     */
    public function label(int $quantity = 1): string
    {
        return match ($this) {
            self::DAY => $quantity === 1 ? 'Día' : 'Días',
            self::WEEK => $quantity === 1 ? 'Semana' : 'Semanas',
            self::MONTH => $quantity === 1 ? 'Mes' : 'Meses',
        };
    }
}
