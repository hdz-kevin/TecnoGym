<?php

namespace App\Livewire;

use App\Enums\DurationUnit;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Plan;
use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use App\Enums\PeriodStatus;
use App\Models\PlanType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

#[Title('Membresías')]
class Memberships extends Component
{
    use WithPagination;

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

    #[Validate('required', message: 'La fecha de inicio es obligatoria')]
    #[Validate('date', message: 'La fecha de inicio debe ser una fecha válida')]
    public $start_date;

    /**
     * Automatically calculated end date of the first period
     *
     * @var Carbon|null
     */
    public Carbon|null $end_date = null;

    /**
     * Available plans for selected plan type
     *
     * @var Collection<Plan>
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
        $this->resetPage();
    }

    /**
     * Reset status filter when searching
     */
    public function updatedSearch()
    {
        $this->statusFilter = null;
        $this->resetPage();
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
            'periods',
        ])
        ->when($this->search, function ($query) {
            $query->whereHas('member', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', $this->search . '%');
            });
        })
        ->when($this->statusFilter, function ($query) {
            $query->where('status', $this->statusFilter->value);
        })
        ->orderBy('updated_at', 'desc')
        ->paginate(6);
    }

    /**
     * Listen for new period added event
     *
     * @return void
     */
    #[On('new-period-added')]
    public function newPeriodAdded()
    {
        $this->reset('search', 'statusFilter');
        $this->memberships;

        session()->flash('message', 'Nuevo periodo añadido');
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
        ]);
    }

    /**
     * Open create membership modal
     */
    public function createMembershipModal()
    {
        $this->showCreateModal = true;
        $this->start_date = now()->format('Y-m-d');

        // Disable background scroll
        $this->dispatch('disable-scroll');
    }

    /**
     * When plan type changes, update available plans
     */
    public function updatedPlanTypeId($planTypeId)
    {
        $this->plan_id = '';

        if ($planTypeId) {
            $this->availablePlans = Plan::where('plan_type_id', $planTypeId)
                                        ->orderBy('duration_in_days')
                                        ->get();
        } else {
            $this->availablePlans = collect([]);
        }

        // Reset end date to calculate it again
        $this->end_date = null;
    }

    /**
     * When plan (period) changes, update end date
     *
     * @param Plan|null $plan
     * @return void
     */
    public function updatedPlanId(Plan|null $plan)
    {
        if ($plan == null) {
            return;
        }

        try {
            $startDate = Carbon::parse($this->start_date);

            $this->end_date = match ($plan->duration_unit) {
                DurationUnit::DAY => $startDate->addDays($plan->duration_value),
                DurationUnit::WEEK => $startDate->addWeeks($plan->duration_value),
                DurationUnit::MONTH => $startDate->addMonths($plan->duration_value),
            };
        } catch (\Exception $e) {
            $this->end_date = null;
        }
    }

    /**
     * When start date changes, update end date
     *
     * @param string $startDate
     * @return void
     */
    public function updatedStartDate($startDate)
    {
        $this->updatedPlanId(Plan::find($this->plan_id));
    }

    /**
     * Save new membership
     */
    public function saveMembership()
    {
        $validated = $this->validate();

        $membership = Membership::create([
            'member_id' => $validated['member_id'],
            'plan_id' => $validated['plan_id'],
            'plan_type_id' => $validated['plan_type_id'],
            'status' => MembershipStatus::ACTIVE,
        ]);

        // Initialize first period
        $membership->periods()->create([
            'start_date' => $validated['start_date'],
            'end_date' => $this->end_date,
            'price_paid' => $membership->plan->price,
            'status' => PeriodStatus::IN_PROGRESS,
        ]);

        // Update member status
        $membership->member->update(['status' => MemberStatus::ACTIVE]);

        $this->closeCreateModal();

        session()->flash('message', 'Membresía creada exitosamente');
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
        $this->end_date = null;
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
