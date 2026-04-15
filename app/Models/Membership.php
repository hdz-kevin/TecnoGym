<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Enums\PeriodStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membership extends Model
{
    protected $fillable = [
        'member_id',
        'membership_type_id',
    ];

    /**
     * Get the membership's status (computed from periods).
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->periods->contains(
                fn ($period) => $period->status === PeriodStatus::IN_PROGRESS
            )
                ? MembershipStatus::ACTIVE
                : MembershipStatus::EXPIRED,
        );
    }

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
        return $this->hasMany(Period::class)->orderBy('id', 'desc');
    }

    /**
     * Get the most recent period.
     *
     * @return Period
     */
    public function getRecentPeriodAttribute()
    {
        return $this->periods->first();
    }

    /**
     * Get the formatted expiration time string.
     */
    public function getExpirationTimeAttribute(): string
    {
        $now = now();
        $endDate = $this->recent_period->end_date;

        return $endDate->locale('es')
            ->diffForHumans($now, [
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
}
