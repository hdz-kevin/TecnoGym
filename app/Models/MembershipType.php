<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipType extends Model
{
    protected $fillable = ['name'];

    /**
     * Get the memberships for the membership type.
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get the available period durations for the membership type.
     */
    public function periodDurations(): HasMany
    {
        return $this->hasMany(PeriodDuration::class)->orderBy('price');
    }
}
