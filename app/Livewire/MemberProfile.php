<?php

namespace App\Livewire;

use App\Models\Member;
use Livewire\Attributes\On;
use Livewire\Component;

class MemberProfile extends Component
{
    public Member|null $member = null;
    public bool $show = false;

    #[On('show-member-profile')]
    public function showProfile(Member $member)
    {
        $this->member = $member;
        $this->show = true;
    }

    public function close(): void
    {
        $this->member = null;
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.member-profile');
    }
}
