<?php

namespace App\Livewire;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Plan;
use App\Enums\MembershipStatus;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Validate;

#[Title('Membresías')]
class Memberships extends Component
{
    // Properties for new membership form
    #[Validate('required', message: 'Elige un socio')]
    #[Validate('exists:members,id', message: 'Elige un socio válido')]
    public $member_id = '';

    #[Validate('required', message: 'Elige un plan')]
    #[Validate('exists:plans,id', message: 'Elige un plan válido')]
    public $plan_id = '';

    // Modal states
    public $showCreateModal = false;
    public $showHistoryModal = false;

    // Selected membership for history view
    public $selectedMembership = null;

    // Available plans for selected member
    public $availablePlans = [];

    /** Current status filter */
    public ?MembershipStatus $statusFilter = null;

    /**
     * Open create membership modal
     */
    public function createMembership()
    {
        $this->showCreateModal = true;
        $this->resetForm();
    }

    /**
     * Show membership history modal
     */
    public function showHistory(Membership $membership)
    {
        $this->selectedMembership = $membership->load([
            'member',
            'plan.planType',
            'periods' => fn($query) => $query->orderBy('start_date', 'desc')
        ]);
        $this->showHistoryModal = true;

        // Disable body scroll
        $this->dispatch('disable-scroll');
    }    /**
     * Close create modal
     */
    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    /**
     * Close history modal
     */
    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->selectedMembership = null;

        // Enable body scroll
        $this->dispatch('enable-scroll');
    }

    /**
     * Reset form fields
     */
    private function resetForm()
    {
        $this->member_id = '';
        $this->plan_id = '';
        $this->availablePlans = [];
    }

    /**
     * When member changes, load available plans
     */
    public function updatedMemberId($value)
    {
        if ($value) {
            $this->availablePlans = Plan::with('planType')
                ->orderByDuration()
                ->get()
                ->groupBy('planType.name');
        } else {
            $this->availablePlans = [];
        }
        $this->plan_id = '';
    }

    /**
     * Save new membership
     */
    public function saveMembership()
    {
        $validated = $this->validate();

        $plan = Plan::find($validated['plan_id']);

        Membership::create([
            'member_id' => $validated['member_id'],
            'plan_id' => $validated['plan_id'],
            'plan_type_id' => $plan->plan_type_id,
            'status' => MembershipStatus::PENDING,
        ]);

        $this->closeCreateModal();
        session()->flash('message', 'Membresía creada exitosamente.');
    }

    /**
     * Filter memberships by status
     */
    public function filterByStatus(?MembershipStatus $status = null)
    {
        $this->statusFilter = $status;
    }

    /**
     * Computed property for memberships list (with filtering and summary data)
     */
    #[Computed]
    public function memberships() {
        return Membership::with([
            'member',
            'plan.planType',
            'periods' => fn($query) => $query->orderBy('start_date', 'desc')
        ])
        ->when($this->statusFilter, function ($query) {
            $query->where('status', $this->statusFilter);
        })
        ->orderBy('status')
        ->get();
    }

    /**
     * Computed property for membership stats (calculated from all memberships)
     */
    #[Computed]
    public function stats() {
        $allMemberships = Membership::all();

        return [
            'total' => $allMemberships->count(),
            'active' => $allMemberships->filter(fn ($m) => $m->status === MembershipStatus::ACTIVE)->count(),
            'expired' => $allMemberships->filter(fn ($m) => $m->status === MembershipStatus::EXPIRED)->count(),
            'pending' => $allMemberships->filter(fn ($m) => $m->status === MembershipStatus::PENDING)->count(),
        ];
    }

    public function render()
    {
        $members = Member::all();

        return view('livewire.memberships', compact('members'));
    }
}
