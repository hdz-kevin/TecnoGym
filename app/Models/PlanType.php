<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanType extends Model
{
    protected $fillable = ['name'];

    /**
     * Get the plans for the plan type.
     */
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * Get the memberships for the membership type.
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}
