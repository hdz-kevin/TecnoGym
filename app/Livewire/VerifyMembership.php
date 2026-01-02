<?php

namespace App\Livewire;

use App\Enums\MembershipStatus;
use App\Models\Member;
use Livewire\Attributes\Rule;
use Livewire\Component;

class VerifyMembership extends Component
{
    #[Rule('required', message: 'El código es obligatorio')]
    #[Rule('size:4', message: 'El código debe tener 4 dígitos')]
    public $code = '';
    public $member = null;
    public $status = null; // 'active', 'expired', 'not_found'
    public $message = '';
    public $showModal = false;

    public function mount()
    {
        $this->code = '0003';
        $this->member = Member::where('code', $this->code)->first();
        $this->status = 'no_membership';
        $this->showModal = true;
    }

    public function check()
    {
        $this->validate();

        $member = Member::where('code', $this->code)->first();

        if (! $member) {
            $this->status = 'not_found';
            $this->message = 'Código no encontrado. Por favor verifique e intente nuevamente.';
            $this->member = null;
            $this->showModal = true;
            return;
        }

        $this->member = $member;
        $activeMembership = $member->activeMembership();
        $latestMembership = $member->latestMembership();

        if ($activeMembership) {
            $this->status = 'active';
            $this->message = '¡Membresía Activa!';
        } elseif ($latestMembership) {
            $this->status = 'expired';
            $this->message = 'Membresía Vencida';
        } else {
            $this->status = 'no_membership';
            $this->message = 'Sin Membresía Asignada';
        }

        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
        $this->reset(['code', 'member', 'status', 'message']);
    }

    public function render()
    {
        return view('livewire.verify-membership');
    }
}
