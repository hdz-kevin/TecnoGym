<?php

namespace App\Livewire;

use App\Enums\DurationUnit;
use App\Enums\MembershipStatus;
use App\Enums\PeriodStatus;
use App\Models\Membership;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

class AddPeriod extends Component
{
    #[Rule('required', message: 'La fecha de inicio es obligatoria')]
    #[Rule('date', message: 'La fecha de inicio debe ser una fecha válida')]
    public $start_date;

    /**
     * The membership to add the period to
     */
    public ?Membership $membership = null;

    /**
     * Modal state
     */
    public $showModal = false;

    /**
     * Open modal when open-add-period-modal event is dispatched
     *
     * @return void
     */
    #[On('open-add-period-modal')]
    public function openModal(Membership $membership)
    {
        $this->membership = $membership->load(['plan']);
        $this->start_date = now()->format('Y-m-d');
        $this->showModal = true;
        $this->dispatch('disable-scroll');
    }

    /**
     * Calculate the end date based on the start date and plan duration.
     *
     * @return \Carbon\Carbon|null
     */
    public function getEndDateProperty()
    {
        if (!$this->membership || !$this->start_date) {
            return null;
        }

        try {
            $endDate = Carbon::parse($this->start_date);
            $durationUnit = $this->membership->plan->duration_unit;
            $durationValue = $this->membership->plan->duration_value;

            return match ($durationUnit) {
                DurationUnit::DAY => $endDate->addDays($durationValue),
                DurationUnit::WEEK => $endDate->addWeeks($durationValue),
                DurationUnit::MONTH => $endDate->addMonths($durationValue),
            };
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Save the new period and update membership status to active
     *
     * @return void
     */
    public function save()
    {
        $this->validate();

        $startDate = Carbon::parse($this->start_date);
        $endDate = $this->endDate;

        $this->membership->periods()->create([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price_paid' => $this->membership->plan->price,
            'status' => PeriodStatus::IN_PROGRESS,
        ]);

        // Update membership status to active
        $this->membership->update([
            'status' => MembershipStatus::ACTIVE,
        ]);

        $this->closeModal();

        // Notify the parent component (Memberships) that a new period has been added
        $this->dispatch('new-period-added');
    }

    /**
     * Close the modal and reset the form
     *
     * @return void
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->membership = null;
        $this->reset(['start_date']);
        $this->dispatch('enable-scroll');
    }

    /**
     * Render the component view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.add-period');
    }
}
