<?php

namespace App\Models;

use App\Enums\DurationUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'duration_value',
        'duration_unit',
        'price',
        'plan_type_id',
    ];

    protected $casts = [
        'duration_unit' => DurationUnit::class,
    ];

    /**
     * Get the plan type that owns the plan.
     */
    public function planType(): BelongsTo
    {
        return $this->belongsTo(PlanType::class);
    }

    /**
     * Get the memberships for the plan.
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}
