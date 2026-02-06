<?php

namespace App\Livewire;

use App\Enums\DurationUnit;
use App\Enums\MembershipStatus;
use App\Enums\PeriodStatus;
use App\Models\Membership;
use App\Models\Period;
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
     * Period being edited
     */
    public ?Period $editingPeriod = null;

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
    public function openModal(Membership $membership, ?int $periodId = null)
    {
        $this->membership = $membership->load(['plan', 'periods']);

        if ($periodId) {
            $this->editingPeriod = $this->membership->periods->findOrFail($periodId);
            $this->start_date = $this->editingPeriod->start_date->format('Y-m-d');
        } else {
            $this->editingPeriod = null;
            $this->start_date = now()->format('Y-m-d');
        }

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

        if ($this->editingPeriod) {
            $this->editingPeriod->update([
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
            $flash = 'Periodo actualizado exitosamente';
        } else {
            $this->membership->periods()->create([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'price_paid' => $this->membership->plan->price,
                'status' => PeriodStatus::IN_PROGRESS,
            ]);

            // Update membership status to active
            // Todo: Should we update membership status only on creation?
            $this->membership->update([
                'status' => MembershipStatus::ACTIVE,
            ]);
            $flash = 'Periodo añadido exitosamente';
        }

        $this->closeModal();

        // Notify the parent component (Memberships) that a period has been added or edited
        $this->dispatch('period-saved', $flash);
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
        $this->editingPeriod = null;
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
