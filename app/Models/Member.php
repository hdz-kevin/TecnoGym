<?php

namespace App\Models;

use App\Enums\MemberGender;
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
        'gender' => MemberGender::class,
    ];

    /**
     * Get the active membership for the member.
     *
     * @return Membership|null If no active membership, returns null.
     */
    public function activeMembership(): Membership|null
    {
        return $this->memberships()->where('status', 'ACTIVE')->with('membershipType')->latest('created_at')->first();
    }

    /**
     * Get the most recent membership for the member.
     *
     * @return Membership|null If no memberships, returns null.
     */
    public function latestMembership(): Membership|null
    {
        return $this->memberships()->with('membershipType')->latest('created_at')->first();
    }

    /**
     * Get the member's age.
     *
     * @return int|null If birth_date is null, returns null.
     */
    public function getAge(): int|null
    {
        if (!$this->birth_date) {
            return null;
        }

        return floor($this->birth_date->diffInYears(now()));
    }

    /**
     * Get the initials of the member.
     *
     * @return string
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
     *
     * @return HasMany
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}
