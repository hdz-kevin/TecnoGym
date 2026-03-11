<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membership extends Model
{
    protected $fillable = [
        'member_id',
        'membership_type_id',
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
     * Get the membership type that owns the membership.
     *
     * @return BelongsTo
     */
    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class);
    }

    /**
     * Get the periods for the membership.
     *
     * @return HasMany
     */
    public function periods()
    {
        return $this->hasMany(Period::class)->orderBy('start_date', 'desc');
    }

    /**
     * Get the current active period.
     *
     * TODO: Refactor -> Why is a hasMany relationship used here?
     *                   It should return a single period.
     *
     * @return HasMany
     */
    public function currentPeriod()
    {
        // future memberships are included
        return $this->hasMany(Period::class)
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
     * Get the formatted expiration time string.
     *
     * @return string|null
     */
    public function getExpirationTimeAttribute(): ?string
    {
        $lastPeriod = $this->last_period;

        if (! $lastPeriod) {
            return null;
        }

        $endDate = $lastPeriod->end_date;

        return $endDate->locale('es')
                       ->diffForHumans(now(), [
                           'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
                           'parts' => 2,
                           'join' => true,
                       ]);
    }

    /**
     * Get the total amount paid for the membership by summing the price_paid of all periods.
     *
     * @return int
     */
    public function getTotalPaidAttribute()
    {
        return $this->periods->sum('price_paid');
    }

    /**
     * Get the plan name.
     *
     * @return string
     */
    public function getPlanNameAttribute()
    {
        return $this->planType->name.' - '.$this->plan->name;
    }
}
