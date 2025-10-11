<?php

namespace App\Models;

use App\Enums\DurationUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Period extends Model
{
    protected $fillable = [
        'name',
        'duration_value',
        'duration_unit',
        'price',
        'membership_type_id',
    ];

    protected $casts = [
        'duration_unit' => DurationUnit::class,
    ];

    /**
     * Get the membership type that owns the period.
     */
    public function membershipType(): BelongsTo
    {
        return $this->belongsTo(MembershipType::class);
    }
}
