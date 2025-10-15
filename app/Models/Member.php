<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'birth_date',
        'photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Get the memberships for the member.
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}
