<?php

namespace App\Livewire;

use App\Enums\DurationUnit;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\Period;
use App\Enums\MembershipStatus;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;

#[Title('Membresías')]
class Memberships extends Component
{
    // Properties
    #[Rule('required', message: 'El socio es obligatorio')]
    public $memberId = '';

    #[Rule('required', message: 'El tipo de membresía es obligatorio')]
    public $membershipTypeId = '';

    #[Rule('required', message: 'El período es obligatorio')]
    public $periodId = '';

    #[Rule('required', message: 'La fecha de inicio es obligatoria')]
    #[Rule('date', message: 'La fecha de inicio debe ser una fecha válida')]
    public $startDate = '';

    // Modal state
    public $showMembershipModal = false;

    /** Updating membership instance or null (no updating by default) */
    public Membership|null $updatingMembership = null;

    // Available periods for selected membership type
    public $availablePeriods = [];

    /**
     * Open create/update membership form modal
     */
    public function openFormModal(Membership $membership)
    {
        if ($membership->exists) {
            $this->updatingMembership = $membership;
        } else {
            $this->showMembershipModal = true;
            $this->startDate = now()->format('Y-m-d');
        }
    }

    /**
     * Save membership (create or update)
     */
    public function saveMembership()
    {
        $this->validate([
            'memberId' => 'required|exists:members,id',
            'membershipTypeId' => 'required|exists:membership_types,id',
            'periodId' => 'required|exists:periods,id',
            'startDate' => 'required|date',
        ]);

        $period = Period::find($this->periodId);
        $startDate = Carbon::parse($this->startDate);

        // Calculate end date based on period duration
        $endDate = match($period->duration_unit) {
            DurationUnit::DAY => $startDate->copy()->addDays($period->duration_value),
            DurationUnit::WEEK => $startDate->copy()->addWeeks($period->duration_value),
            DurationUnit::MONTH => $startDate->copy()->addMonths($period->duration_value),
            DurationUnit::YEAR => $startDate->copy()->addYears($period->duration_value),
        };

        $membershipData = [
            'member_id' => $this->memberId,
            'membership_type_id' => $this->membershipTypeId,
            'period_id' => $this->periodId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price' => $period->price,
            'status' => $endDate->isFuture() ? MembershipStatus::ACTIVE : MembershipStatus::EXPIRED,
        ];

        if ($this->updatingMembership) {
            $this->updatingMembership->update($membershipData);
            $message = 'Membresía actualizada exitosamente.';
        } else {
            Membership::create($membershipData);
            $message = 'Membresía creada exitosamente.';
        }

        $this->closeMembershipModal();
        session()->flash('message', $message);
    }

    /**
     * Update available periods when membership type changes
     */
    public function updatedMembershipTypeId()
    {
        if ($this->membershipTypeId) {
            $this->availablePeriods = Period::where('membership_type_id', $this->membershipTypeId)->get();
            $this->periodId = ''; // Reset period selection
        } else {
            $this->availablePeriods = [];
            $this->periodId = '';
        }
    }

    /**
     * Close membership modal and reset form
     */
    public function closeMembershipModal()
    {
        $this->showMembershipModal = false;
        $this->updatingMembership = null;
        $this->resetMembershipForm();
        $this->resetValidation();
    }

    /**
     * Reset membership form fields
     */
    private function resetMembershipForm()
    {
        $this->memberId = '';
        $this->membershipTypeId = '';
        $this->periodId = '';
        $this->startDate = '';
        $this->availablePeriods = [];
    }

    public function render()
    {
        $memberships = Membership::with(['member', 'membershipType', 'period'])->get();
        $activeCount = $memberships->where('status', 'active')->count();
        $expiredCount = $memberships->where('status', 'expired')->count();

        $members = Member::orderBy('name')->get();
        $membershipTypes = MembershipType::with('periods')->orderBy('name')->get();

        return view(
            'livewire.memberships',
            compact('memberships', 'activeCount', 'expiredCount', 'members', 'membershipTypes'),
        );
    }
}
