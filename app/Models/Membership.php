<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    protected $fillable = [
        'member_id',
        'plan_id',
        'plan_type_id',
        'status',
    ];

    protected $casts = [
        'status' => MembershipStatus::class,
    ];

    /**
     * Get the member that owns the membership.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the plan that owns the membership.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the plan type that owns the membership.
     */
    public function planType(): BelongsTo
    {
        return $this->belongsTo(PlanType::class);
    }
}
