<?php

namespace App\Livewire;

use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use App\Enums\PeriodStatus;
use App\Models\Duration;
use App\Models\Membership;
use App\Models\Period;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

/**
 * This component handles the membership renewals by adding new periods, and period edition
 */
class AddPeriod extends Component
{
    #[Rule('required', message: 'La fecha de inicio es obligatoria')]
    #[Rule('date', message: 'La fecha de inicio debe ser una fecha válida')]
    public $start_date;

    #[Rule('required', message: 'Selecciona una duración')]
    #[Rule('exists:durations,id', message: 'Selecciona una duración válida')]
    public $duration_id;

    /**
     * The membership to renew
     */
    public ?Membership $membership = null;

    /**
     * The durations of the membership type
     */
    public $durations = [];

    /**
     * Period being edited
     */
    public ?Period $editingPeriod = null;

    /**
     * Modal state
     */
    public $showModal = false;

    /**
     * Open form modal and initialize the properties for create or edit a period
     *
     * @param Membership $membership - The membership to renew adding a new period if `$period` is null
     * @param Period|null $period - The period to edit if provided
     * @return void
     */
    #[On('open-period-modal')]
    public function openModal(Membership $membership, ?Period $period = null)
    {
        if ($period?->status == PeriodStatus::COMPLETED) {
            $this->dispatch('error-alert', 'No se pueden editar periodos completados');
            return;
        }

        $this->membership = $membership->load(['membershipType.durations']);
        $this->durations = $this->membership->membershipType->durations;

        if (! empty($period->attributesToArray())) {
            $this->editingPeriod = $period;
            $this->duration_id = $period->duration_id;
            $this->start_date = $period->start_date->format('Y-m-d');
        } else {
            $this->start_date = now()->format('Y-m-d');
        }

        $this->showModal = true;
        $this->dispatch('disable-scroll');
    }

    /**
     * Save the new period and update membership status to active
     *
     * @return void
     */
    public function save()
    {
        $validated = $this->validate();

        /** @var Duration */
        $duration = Duration::find($validated['duration_id']);
        $startDate = Carbon::parse($this->start_date);
        $endDate = Period::calculateEndDate($startDate, $duration);

        $periodData = [
            'duration_id' => $duration->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price_paid' => $duration->price,
            'status' => PeriodStatus::IN_PROGRESS,
        ];

        if ($this->editingPeriod) {
            $this->editingPeriod->update($periodData);
            $this->membership->setUpdatedAt(now())->save();

            $flash = 'Periodo actualizado exitosamente';
        } else {
            $this->membership->periods()->create($periodData);
            $this->membership->update(['status' => MembershipStatus::ACTIVE]);
            $this->membership->member->update(['status' => MemberStatus::ACTIVE]);

            $flash = 'Membresía renovada exitosamente';
        }

        $this->closeModal();

        // Notify the Memberships and MembershipHistory that the membership has been renewed
        $this->dispatch('renewed-membership', $flash);
    }

    /**
     * Close the modal and reset properties
     *
     * @return void
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->membership = null;
        $this->durations = [];
        $this->editingPeriod = null;
        $this->reset(['start_date', 'duration_id']);
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
