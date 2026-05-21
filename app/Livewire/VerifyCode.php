<?php

namespace App\Livewire;

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\Membership;
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

    /**
     * The member that owns the code.
     */
    public ?Member $member = null;

    /**
     * The latest membership of the member.
     */
    public ?Membership $membership = null;

    /**
     * Indicates whether the verification result should be shown.
     */
    public bool $showResult = false;

    /**
     * Verifies the entered code and shows the result.
     */
    public function check(): void
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

    /**
     * Clears the form and resets the result.
     */
    public function clear(): void
    {
        $this->showResult = false;
        $this->reset(['code', 'member', 'membership']);
        $this->resetValidation();
        $this->dispatch('focus-code-input');
    }

    /**
     * Renders the verify code view.
     */
    public function render()
    {
        return view('livewire.verify-code', [
            'gymName'    => config('gym.name'),
            'gymAddress' => config('gym.address'),
        ]);
    }
}
