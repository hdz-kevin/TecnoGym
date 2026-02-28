<?php

namespace App\Models;

use App\Enums\DurationUnit;
use App\Enums\PeriodStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Period extends Model
{
    protected $fillable = [
        'membership_id',
        'period_type_id',
        'start_date',
        'end_date',
        'price_paid',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => PeriodStatus::class,
    ];

    /**
     * Get the membership that owns the period.
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Get the period type that owns the period.
     */
    public function periodType(): BelongsTo
    {
        return $this->belongsTo(PeriodType::class);
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
     * Calculate the end date of a period based on the start date and period type.
     *
     * @param Carbon $startDate
     * @param PeriodType $periodType
     * @return Carbon
     */
    public static function calculateEndDate(Carbon $startDate, PeriodType $periodType): Carbon
    {
        $startDate = $startDate->copy();

        return match ($periodType->duration_unit) {
            DurationUnit::DAY => $startDate->addDays($periodType->duration_value),
            DurationUnit::WEEK => $startDate->addWeeks($periodType->duration_value),
            DurationUnit::MONTH => $startDate->addMonths($periodType->duration_value),
        };
    }
}
