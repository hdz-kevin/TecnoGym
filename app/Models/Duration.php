<?php

namespace App\Models;

use App\Enums\DurationUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Duration extends Model
{
    protected $fillable = [
        'membership_type_id',
        'name',
        'amount',
        'unit',
        'price',
    ];

    protected $casts = [
        'unit' => DurationUnit::class,
    ];

    /**
     * Get the membership type that owns the duration
     *
     * @return BelongsTo
     */
    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class);
    }

    /**
     * Get the periods associated with the duration
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
    public function getFormattedAttribute(): string
    {
        return $this->amount . ' ' . $this->unit->label($this->amount);
    }
}
