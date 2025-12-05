<?php

namespace App\Livewire;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Plan;
use App\Enums\MembershipStatus;
use App\Models\PlanType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
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

    #[Validate('required', message: 'Elige un tipo de plan')]
    #[Validate('exists:plan_types,id', message: 'Elige un tipo de plan válido')]
    public $plan_type_id = '';

    #[Validate('required', message: 'Elige un plan')]
    #[Validate('exists:plans,id', message: 'Elige un plan válido')]
    public $plan_id = '';

    /**
     * Available plans for selected plan type
     */
    public $availablePlans;

    /**
     * Current status filter for memberships list
     */
    public ?MembershipStatus $statusFilter = null;

    /**
     * Membership search by member name
     */
    public $search = '';

    /**
     * Create membership modal state
     */
    public $showCreateModal = false;

    /**
     * Mount the component
     */
    public function mount()
    {
        $this->availablePlans = collect([]);
    }

    /**
     * Update status filter
     */
    public function setStatusFilter(MembershipStatus|null $status = null)
    {
        $this->statusFilter = $status;
        $this->search = '';
    }

    /**
     * Reset status filter when searching
     */
    public function updatedSearch()
    {
        $this->statusFilter = null;
    }

    /**
     * Get filtered and ordered memberships list
     *
     * @return Collection<Membership>
     */
    #[Computed]
    public function memberships()
    {
        return Membership::with([
            'member',
            'plan',
            'planType',
            'periods' => fn($query) => $query->orderBy('start_date', 'desc')
        ])
        ->when($this->search, function ($query) {
            $query->whereHas('member', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->statusFilter, function ($query) {
            $query->where('status', $this->statusFilter->value);
        })
        ->orderBy('status')
        ->get();
    }

    /**
     * Get memberships statistics
     *
     * @return SupportCollection<string, int>
     */
    #[Computed]
    public function stats()
    {
        $allMemberships = Membership::all();

        return collect([
            'total' => $allMemberships->count(),
            'active' => $allMemberships->filter(fn ($m) => $m->status === MembershipStatus::ACTIVE)->count(),
            'expired' => $allMemberships->filter(fn ($m) => $m->status === MembershipStatus::EXPIRED)->count(),
            'pending' => $allMemberships->filter(fn ($m) => $m->status === MembershipStatus::PENDING)->count(),
        ]);
    }

    /**
     * Open create membership modal
     */
    public function createMembershipModal()
    {
        $this->showCreateModal = true;

        // Disable background scroll
        $this->dispatch('disable-scroll');
    }

    /**
     * When plan type changes, update available plans
     */
    public function updatedPlanTypeId($value)
    {
        $this->plan_id = '';

        if ($value) {
            $this->availablePlans = Plan::where('plan_type_id', $value)
                                        ->orderBy('duration_in_days')
                                        ->get();
        } else {
            $this->availablePlans = collect([]);
        }
    }

    /**
     * Save new membership
     */
    public function saveMembership()
    {
        $validated = $this->validate();

        Membership::create([
            'member_id' => $validated['member_id'],
            'plan_id' => $validated['plan_id'],
            'plan_type_id' => $validated['plan_type_id'],
            'status' => MembershipStatus::PENDING,
        ]);

        $this->closeCreateModal();
    }

    /**
     * Close create modal
     */
    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();

        // Enable background scroll
        $this->dispatch('enable-scroll');
    }

    /**
     * Reset form fields
     */
    private function resetForm()
    {
        $this->member_id = '';
        $this->plan_type_id = '';
        $this->plan_id = '';
        $this->availablePlans = collect([]);
    }

    /**
     * Render the component view
     */
    public function render()
    {
        $members = Member::all();
        $planTypes = PlanType::with('plans')->get();

        return view('livewire.memberships', compact('members', 'planTypes'));
    }
}
