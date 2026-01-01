<?php

namespace App\Livewire;

use App\Enums\MembershipStatus;
use App\Models\Member;
use Livewire\Component;

class CheckStatus extends Component
{
    public $code = '';
    public $member = null;
    public $status = null; // 'active', 'expired', 'not_found'
    public $message = '';

    public function check()
    {
        $this->validate([
            'code' => 'required|string|min:4',
        ]);

        $member = Member::where('code', $this->code)->first();

        if (! $member) {
            $this->status = 'not_found';
            $this->message = 'Código no encontrado. Por favor verifique e intente nuevamente.';
            $this->member = null;

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
    }

    public function render()
    {
        return view('livewire.check-status');
    }
}
