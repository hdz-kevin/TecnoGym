<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the memberships for the member.
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}
