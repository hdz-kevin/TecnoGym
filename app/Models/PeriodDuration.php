<?php

namespace App\Models;

use App\Enums\DurationUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodDuration extends Model
{
    protected $fillable = [
        'membership_type_id',
        'name',
        'duration_value',
        'duration_unit',
        'price',
    ];

    protected $casts = [
        'duration_unit' => DurationUnit::class,
    ];

    /**
     * Get the membership type that owns the period duration
     *
     * @return BelongsTo
     */
    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class);
    }

    /**
     * Get the periods for the period duration
     *
     * @return HasMany
     */
    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    /**
     * Get formatted duration string (e.g., "15 días", "1 mes", "3 meses").
     */
    public function getFormattedDurationAttribute(): string
    {
        return $this->duration_value . ' ' . $this->duration_unit->label($this->duration_value);
    }
}
