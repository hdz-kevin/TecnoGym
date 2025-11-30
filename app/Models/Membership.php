<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Enums\PeriodStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     *
     * @return BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the plan that owns the membership.
     *
     * @return BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the plan type that owns the membership.
     *
     * @return BelongsTo
     */
    public function planType()
    {
        return $this->belongsTo(PlanType::class);
    }

    /**
     * Get the periods for the membership.
     *
     * @return HasMany
     */
    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    /**
     * Get the current active period.
     *
     * @return HasMany
     */
    public function currentPeriod()
    {
        return $this->hasMany(Period::class)
                    // future memberships are included
                    // ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->latest('start_date');
    }

    /**
     * Get the last period.
     *
     * @return Period|null
     */
    public function getLastPeriodAttribute()
    {
        return $this->periods->first();
    }

    /**
     * Get total amount paid for completed periods.
     *
     * @return int
     */
    public function getTotalPaidAttribute()
    {
        return $this->periods->where('status', PeriodStatus::COMPLETED)->sum('price_paid');
    }
}
