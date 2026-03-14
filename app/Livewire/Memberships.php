<?php

namespace App\Livewire;

use App\Models\Member;
use App\Models\Membership;
use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use App\Enums\PeriodStatus;
use App\Models\Duration;
use App\Models\MembershipType;
use App\Models\Period;

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

    // Properties for the new membership
    #[Validate('required', message: 'Elige un socio')]
    #[Validate('exists:members,id', message: 'Elige un socio válido')]
    public $member_id = '';

    #[Validate('required', message: 'Elige un tipo de membresía')]
    #[Validate('exists:membership_types,id', message: 'Elige un tipo de membresía válido')]
    public $membership_type_id = '';

    #[Validate('required', message: 'Elige una duración')]
    #[Validate('exists:durations,id', message: 'Elige una duración válida')]
    public $duration_id = '';

    #[Validate('required', message: 'Elige una fecha de inicio')]
    #[Validate('date', message: 'Elige una fecha de inicio válida')]
    public $start_date = '';

    /**
     * Available durations for selected membership type
     *
     * @var Collection<Duration>
     */
    public $durations;

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
        $this->durations = collect([]);
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
     * When membership type changes, update available durations
     *
     * @param mixed $membershipTypeId
     * @return void
     */
    public function updatedMembershipTypeId($membershipTypeId)
    {
        $this->durations = Duration::where('membership_type_id', $membershipTypeId)
                                   ->orderBy('price')
                                   ->get();

        $this->duration_id = '';
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

        $duration = Duration::find($validated['duration_id']);

        // Initialize first period
        $membership->periods()->create([
            'duration_id' => $duration->id,
            'start_date' => $validated['start_date'],
            'end_date' => Period::calculateEndDate(Carbon::parse($validated['start_date']), $duration),
            'price_paid' => $duration->price,
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
        $this->duration_id = '';
        $this->start_date = '';
        $this->durations = collect([]);
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
