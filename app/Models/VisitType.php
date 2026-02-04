<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisitType extends Model
{
    protected $fillable = [
        'name',
        'price',
    ];

    /**
     * Get the visits for the visit type.
     *
     * @return HasMany
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
}
