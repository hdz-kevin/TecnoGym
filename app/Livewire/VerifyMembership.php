<?php

namespace App\Livewire;

use App\Enums\MembershipStatus;
use App\Models\Member;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('')]
class VerifyMembership extends Component
{
    #[Rule('required', message: 'El código es obligatorio')]
    #[Rule('size:5', message: 'El código debe tener 5 dígitos')]
    public $code = '';
    public $member = null;
    public $membership = null;
    public $showModal = false;

    public function check()
    {
        $this->validate();

        $member = Member::where('code', $this->code)->first();

        if (! $member) {
            $this->showModal = true;
            return;
        }

        $this->member = $member;
        $this->membership = $member->latestMembership();

        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
        $this->reset(['code', 'member', 'membership']);
    }

    public function render()
    {
        return view('livewire.verify-membership');
    }
}
