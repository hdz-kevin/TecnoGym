<?php

namespace App\Livewire;

use App\Enums\DurationUnit;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\Period;
use App\Enums\MembershipStatus;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\Validate;

#[Title('Membresías')]
class Memberships extends Component
{
    // Properties
    #[Validate('required', message: 'Elige un socio')]
    #[Validate('exists:members,id', message: 'Elige un socio válido')]
    public $member_id = '';

    #[Validate('required', message: 'Elige un tipo de membresía')]
    #[Validate('exists:membership_types,id', message: 'Elige un tipo de membresía válido')]
    public $membership_type_id = '';

    #[Validate('required', message: 'Elige un período')]
    #[Validate('exists:periods,id', message: 'Elige un período de tiempo válido')]
    public $period_id = '';

    #[Validate('required', message: 'La fecha de inicio es obligatoria')]
    #[Validate('date', message: 'La fecha de inicio debe ser una fecha válida')]
    public $start_date = '';

    // Modal state
    public $showModal = false;

    /** Updating membership instance or null (no updating by default) */
    public Membership|null $updatingMembership = null;

    // Available periods for selected membership type
    public $availablePeriods = [];

    /**
     * Open create/update membership form modal
     */
    public function openModal(Membership $membership)
    {
        $this->showModal = true;

        if ($membership->exists) {
            $this->availablePeriods = Period::where('membership_type_id', $membership->membership_type_id)->get();

            $this->updatingMembership = $membership;
            $this->member_id = $membership->member_id;
            $this->membership_type_id = $membership->membership_type_id;
            $this->period_id = $membership->period_id;
            $this->start_date = $membership->start_date->format('Y-m-d');
        } else {
            $this->start_date = now()->format('Y-m-d');
        }
    }

    /**
     * Save membership (create or update)
     */
    public function saveMembership()
    {
        $this->validate();

        $period = Period::find($this->period_id);
        $startDate = Carbon::parse($this->start_date);

        // Calculate end date based on period duration
        $endDate = match($period->duration_unit) {
            DurationUnit::DAY => $startDate->copy()->addDays($period->duration_value),
            DurationUnit::WEEK => $startDate->copy()->addWeeks($period->duration_value),
            DurationUnit::MONTH => $startDate->copy()->addMonths($period->duration_value),
            DurationUnit::YEAR => $startDate->copy()->addYears($period->duration_value),
        };

        $membershipData = [
            'member_id' => $this->member_id,
            'membership_type_id' => $this->membership_type_id,
            'period_id' => $this->period_id,
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
        if ($this->membership_type_id) {
            $this->availablePeriods = Period::where('membership_type_id', $this->membership_type_id)->get();
            $this->period_id = '';
        } else {
            $this->availablePeriods = [];
            $this->period_id = '';
        }
    }

    /**
     * Close membership modal and reset form
     */
    public function closeMembershipModal()
    {
        $this->showModal = false;
        $this->updatingMembership = null;
        $this->resetMembershipForm();
        $this->resetValidation();
    }

    /**
     * Reset membership form fields
     */
    private function resetMembershipForm()
    {
        $this->member_id = '';
        $this->membership_type_id = '';
        $this->period_id = '';
        $this->start_date = '';
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
