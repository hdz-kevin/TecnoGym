<?php

namespace App\Models;

use App\Enums\DurationUnit;
use App\Enums\PeriodStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Period extends Model
{
    protected $fillable = [
        'membership_id',
        'duration_id',
        'start_date',
        'end_date',
        'price_paid',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the period's status (computed from dates).
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => now()->isAfter($this->end_date)
                ? PeriodStatus::COMPLETED
                : PeriodStatus::IN_PROGRESS,
        );
    }

    /**
     * The "booted" method of the model.
     *
     * Apply the format to the start and end dates before saving
     *  
     * @return void
     */
    protected static function booted(): void
    {
        static::saving(function (Period $period) {
            $period->start_date = $period->start_date->startOfDay();
            $period->end_date = $period->end_date->endOfDay();
        });
    }

    /**
     * Get the membership that owns the period.
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Get the duration assigned to the period
     */
    public function duration(): BelongsTo
    {
        return $this->belongsTo(Duration::class);
    }

    /**
     * Get formatted period string.
     */
    public function getFormattedPeriodAttribute(): string
    {
        $start = $this->start_date->locale('es');
        $end = $this->end_date->locale('es');

        $startMonth = str_replace('.', '', ucfirst($start->translatedFormat('M')));
        $endMonth = str_replace('.', '', ucfirst($end->translatedFormat('M')));

        return $start->translatedFormat('d').' '.$startMonth.' '.$start->translatedFormat('Y')
                .' - '.
                $end->translatedFormat('d').' '.$endMonth.' '.$end->translatedFormat('Y');
    }

    /**
     * Get the end date for a period based on the `$startDate` and `$duration` model
     *
     * @param Carbon $startDate
     * @param Duration $duration
     * @return Carbon
     */
    public static function endDateFrom(Carbon $startDate, Duration $duration): Carbon
    {
        $startDate = $startDate->copy();

        $endDate = match ($duration->unit) {
            DurationUnit::DAY => $startDate->addDays($duration->amount),
            DurationUnit::WEEK => $startDate->addWeeks($duration->amount),
            DurationUnit::MONTH => $startDate->addMonths($duration->amount),
        };
        
        return $endDate->endOfDay();
    }
}
