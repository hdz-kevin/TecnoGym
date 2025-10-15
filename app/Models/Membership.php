<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    protected $fillable = [
        'member_id',
        'membership_type_id',
        'period_id',
        'price',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'status' => MembershipStatus::class,
    ];

    // Relationships

    /**
     * Get the member that owns the membership.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the membership type that owns the membership.
     */
    public function membershipType(): BelongsTo
    {
        return $this->belongsTo(MembershipType::class);
    }

    /**
     * Get the period that owns the membership.
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }
}
