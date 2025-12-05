<?php

namespace App\Livewire;

use App\Models\Membership;
use Livewire\Component;
use Livewire\Attributes\On;

class MembershipHistory extends Component
{
    /**
     * History modal state
     *
     * @var bool
     */
    public $showModal = false;

    /**
     * The Membership model instance currently being viewed.
     *
     * @var Membership|null
     */
    public $membership = null;

    /**
     * Opens the history modal for a specific membership.
     * Eager loads necessary relationships to display the full history.
     *
     * @param Membership $membership
     * @return void
     */
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

    /**
     * Closes the modal and clears the component state.
     *
     * @return void
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->membership = null;
        $this->dispatch('enable-scroll');
    }

    /**
     * Renders the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.membership-history');
    }
}
