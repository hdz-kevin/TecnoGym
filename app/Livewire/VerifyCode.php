<?php

namespace App\Livewire;

use App\Enums\MembershipStatus;
use App\Models\Member;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Bienvenida')]
class VerifyCode extends Component
{
    #[Rule('required', message: 'El código es obligatorio')]
    #[Rule('size:5', message: 'El código debe ser de 5 dígitos')]
    public $code = '';
    public $member = null;
    public $membership = null;
    public bool $showResult = false;

    public function check()
    {
        $this->validate();

        $member = Member::where('code', $this->code)->first();

        if (! $member) {
            $this->showResult = true;
            $this->dispatch('play-sound', status: 'error');
            return;
        }

        $this->member = $member;
        $this->membership = $member->latestMembership();

        if (! $this->membership || $this->membership->status == MembershipStatus::EXPIRED) {
            $this->dispatch('play-sound', status: 'error');
        } else {
            $this->dispatch('play-sound', status: 'success');
        }

        $this->showResult = true;
    }

    public function clear()
    {
        $this->showResult = false;
        $this->reset(['code', 'member', 'membership']);
        $this->resetValidation();
        $this->dispatch('focus-code-input');
    }

    public function render()
    {
        return view('livewire.verify-code', [
            'gymName'    => config('gym.name'),
            'gymAddress' => config('gym.address'),
        ]);
    }
}
