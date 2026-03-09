<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipType extends Model
{
    protected $fillable = ['name'];

    /**
     * Get the memberships for the membership type
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get the available durations for the membership type
     */
    public function durations(): HasMany
    {
        return $this->hasMany(Duration::class)->orderBy('price');
    }
}
