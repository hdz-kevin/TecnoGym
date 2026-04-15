<?php

namespace App\Enums;

enum PeriodStatus: string
{
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

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
