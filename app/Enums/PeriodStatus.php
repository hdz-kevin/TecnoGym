<?php

namespace App\Enums;

use Carbon\Carbon;

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

    /**
     * Determine the period status based on start and end dates.
     */
    public static function fromDates(Carbon $startDate, Carbon $endDate): self
    {
        $now = now();
        
        if ($now->isAfter($endDate->endOfDay())) {
            return self::COMPLETED;
        }

        // If current date is within the range or before start_date
        return self::IN_PROGRESS;
    }
}
