<?php

namespace App\Livewire;

use App\Models\Member;
use App\Models\Membership;
use Livewire\Component;
use Livewire\Attributes\On;

class MembershipDetails extends Component
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

    public function mount()
    {
        $this->showModal = true;
        $this->membership = Member::where('code', '19044')->first()->latestMembership();
        // $this->membership = Member::where('code', '65319')->first()->latestMembership();
        // $this->membership = Member::where('code', '45043')->first()->latestMembership();
    }

    /**
     * Opens the history modal for a specific membership.
     * Eager loads necessary relationships to display the full history.
     *
     * @param Membership $membership
     * @return void
     */
    #[On('open-details-modal')]
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
        return view('livewire.membership-details');
    }
}
