<?php

namespace App\Models;

use App\Enums\PeriodStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Period extends Model
{
    protected $fillable = [
        'membership_id',
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
}
