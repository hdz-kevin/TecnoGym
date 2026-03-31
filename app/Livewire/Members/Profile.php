<?php

namespace App\Livewire\Members;

use App\Models\Member;
use Livewire\Attributes\On;
use Livewire\Component;

class Profile extends Component
{
    public Member|null $member = null;
    public bool $show = false;

    #[On('show-profile')]
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
        return view('livewire.members.profile');
    }
}
