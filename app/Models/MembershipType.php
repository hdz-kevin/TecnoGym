<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipType extends Model
{
    protected $fillable = ['name'];

    /**
     * Get the periods for the membership type.
     */
    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }
}
