<?php

namespace App\Models;

use App\Enums\MemberGender;
use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'status',
        'gender',
        'birth_date',
        'photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'status' => MemberStatus::class,
        'gender' => MemberGender::class,
    ];

    /**
     * Get the memberships for the member.
     *
     * @return HasMany
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get the active membership for the member.
     *
     * @return Membership|null If no active membership, returns null.
     */
    public function activeMembership(): Membership|null
    {
        if ($this->memberships->count() == 0) {
            return null;
        }

        return $this->memberships()
                    ->where('status', MembershipStatus::ACTIVE)
                    ->first();
    }

    /**
     * Get the most recent membership for the member.
     *
     * @return Membership|null If no memberships, returns null.
     */
    public function latestMembership(): Membership|null
    {
        if ($this->memberships->count() == 0) {
            return null;
        }

        return $this->memberships()
                    ->orderBy('updated_at', 'desc')
                    ->first();
    }

    /**
     * Generate a code based on the member's id and save it.
     * The code is a 4-digit number padded with zeros.
     *
     * @return string
     */
    public function generateCode(): string
    {
        $this->code = Str::padLeft($this->id, 4, '0');
        $this->save();

        return $this->code;
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
     * Get the member's antiquity in months.
     *
     * @return int
     */
    public function getAntiquityAttribute(): int
    {
        return floor($this->created_at->diffInMonths(now()));
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
}
