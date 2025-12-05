<?php

namespace App\Livewire;

use App\Models\Membership;
use Livewire\Component;
use Livewire\Attributes\On;

class MembershipHistory extends Component
{
    public $showModal = false;
    public $membership = null;

    #[On('open-history-modal')]
    public function openModal(Membership $membership)
    {
        $this->membership = $membership->load([
            'member',
            'plan',
            'planType',
            'periods',
        ]);

        $this->showModal = true;
        $this->dispatch('disable-scroll');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->membership = null;
        $this->dispatch('enable-scroll');
    }

    public function render()
    {
        return view('livewire.membership-history');
    }
}
