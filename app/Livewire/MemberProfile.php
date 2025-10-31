<?php

namespace App\Livewire;

use App\Models\Member;
use Livewire\Attributes\On;
use Livewire\Component;

class MemberProfile extends Component
{
    public $member = null;
    public $show = false;

    #[On('show-member-profile')]
    public function showProfile($memberId)
    {
        $this->member = Member::with(['memberships.membershipType'])->find($memberId);
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->member = null;
    }

    public function getActiveMembershipProperty()
    {
        if (!$this->member) {
            return null;
        }

        // Get the most recent membership
        return $this->member->memberships()
            ->with('membershipType')
            ->latest('created_at')
            ->first();
    }

    public function getMemberAgeProperty()
    {
        if (!$this->member || !$this->member->birth_date) {
            return null;
        }

        return now()->diffInYears($this->member->birth_date);
    }

    public function getMemberInitialsProperty()
    {
        if (!$this->member) {
            return '?';
        }

        $names = explode(' ', $this->member->name);
        return collect($names)
            ->map(fn($name) => strtoupper(substr($name, 0, 1)))
            ->take(2)
            ->join('');
    }

    public function render()
    {
        return view('livewire.member-profile');
    }
}
