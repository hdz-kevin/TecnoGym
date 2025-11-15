<?php

namespace App\Enums;

enum PeriodStatus: string
{
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    /**
     * Get all enum values as an array
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * Get the label for the period status
     */
    public function label(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'En Curso',
            self::COMPLETED => 'Completado',
        };
    }
}
