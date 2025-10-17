<?php

namespace App\Livewire;

use App\Models\Membership;
use Livewire\Component;

class Memberships extends Component
{
    public function render()
    {
        $memberships = Membership::with(['member', 'membershipType', 'period'])->get();
        $active = $memberships->where('status', 'active');
        $expired = $memberships->where('status', 'expired');

        return view(
            'livewire.memberships',
            compact('memberships', 'active', 'expired'),
        );
    }
}
