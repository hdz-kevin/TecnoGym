<?php

namespace App\Livewire;

use App\Models\Member;
use App\Models\Membership;
use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use App\Enums\PeriodStatus;
use App\Models\MembershipType;
use App\Models\Period;
use App\Models\PeriodDuration;
use App\Models\PeriodType;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Membresías')]
class Memberships extends Component
{
    use WithPagination;

    // Properties for new membership form
    #[Validate('required', message: 'Elige un socio')]
    #[Validate('exists:members,id', message: 'Elige un socio válido')]
    public int|string $member_id = '';

    #[Validate('required', message: 'Elige un tipo de membresía')]
    #[Validate('exists:membership_types,id', message: 'Elige un tipo de membresía válido')]
    public int|string $membership_type_id = '';

    #[Validate('required', message: 'Elige una duración')]
    #[Validate('exists:period_durations,id', message: 'Elige una duración válida')]
    public int|string $period_duration_id = '';

    #[Validate('required', message: 'La fecha de inicio es obligatoria')]
    #[Validate('date', message: 'La fecha de inicio debe ser una fecha válida')]
    public string $start_date = '';

    /**
     * Available period durations for selected membership type
     *
     * @var Collection<PeriodDuration>
     */
    public $periodDurations;

    /**
     * Current status filter for memberships list
     */
    public ?MembershipStatus $statusFilter = null;

    /**
     * Membership search by member name or code
     */
    public ?string $search = null;

    /**
     * Create-membership modal state
     */
    public bool $showCreateModal = false;

    /**
     * Mount the component
     */
    public function mount()
    {
        $this->periodDurations = collect([]);
    }

    /**
     * Update status filter
     *
     * @param ?MembershipStatus $status
     * @return void
     */
    public function setStatusFilter(?MembershipStatus $status = null)
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
     * Open create-membership modal
     */
    public function createMembershipModal()
    {
        $this->showCreateModal = true;
        $this->start_date = now()->format('Y-m-d');

        // Disable background scroll
        $this->dispatch('disable-scroll');
    }

    /**
     * When membership type changes, update available period durations
     *
     * @param int|string $membershipTypeId
     * @return void
     */
    public function updatedMembershipTypeId(int|string $membershipTypeId)
    {
        $this->period_duration_id = '';

        if (! $membershipTypeId) {
            $this->periodDurations = collect([]);
            return;
        }

        $this->periodDurations = PeriodDuration::where('membership_type_id', $membershipTypeId)
                                       ->orderBy('price')
                                       ->get();
    }

    /**
     * Save new membership
     */
    public function saveMembership()
    {
        $validated = $this->validate();

        $membership = Membership::create([
            'member_id' => $validated['member_id'],
            'membership_type_id' => $validated['membership_type_id'],
            'status' => MembershipStatus::ACTIVE,
        ]);

        // Initialize first period
        $periodDuration = PeriodDuration::find($validated['period_duration_id']);

        $membership->periods()->create([
            'period_duration_id' => $validated['period_duration_id'],
            'start_date' => $validated['start_date'],
            'end_date' => Period::calculateEndDate(Carbon::parse($validated['start_date']), $periodDuration),
            'price_paid' => $periodDuration->price,
            'status' => PeriodStatus::IN_PROGRESS,
        ]);

        // Update member status
        $membership->member->update(['status' => MemberStatus::ACTIVE]);

        $this->closeCreateModal();

        session()->flash('message', 'Membresía creada exitosamente');
    }

    /**
     * Close create-membership modal
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
        $this->membership_type_id = '';
        $this->period_duration_id = '';
        $this->start_date = '';
        $this->periodDurations = collect([]);
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
            'membershipType',
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
        ->orderBy('id', 'desc')
        ->paginate(6);
    }

    /**
     * Listen for new period added or edited
     *
     * @return void
     */
    #[On('period-saved')]
    public function periodSaved(string $message)
    {
        $this->reset('search', 'statusFilter');
        $this->resetPage();

        session()->flash('message', $message);
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
     * Render the component view
     */
    public function render()
    {
        $membershipTypes = MembershipType::all();
        $members = Member::orderBy('name')->get();

        return view('livewire.memberships', compact('members', 'membershipTypes'));
    }
}
