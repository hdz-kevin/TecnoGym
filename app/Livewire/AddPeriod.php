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
 * Handle the renewal of a membership by adding new periods
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
     * Open modal when open-add-period-modal event is dispatched
     *
     * @param Membership $membership
     * @param int|null $periodId
     * @return void
     */
    #[On('open-add-period-modal')]
    public function openModal(Membership $membership, ?int $periodId = null)
    {
        $this->membership = $membership->load(['periods', 'membershipType']);
        $this->durations = $this->membership->membershipType->durations;
        $this->start_date = now()->format('Y-m-d');

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
        $duration = Duration::find($this->duration_id);
        $startDate = Carbon::parse($this->start_date);
        $endDate = Period::calculateEndDate($startDate, $duration);

        if ($this->editingPeriod) {
            $this->editingPeriod->update([
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
            $flash = 'Periodo actualizado exitosamente';
        } else {
            $this->membership->periods()->create([
                'duration_id' => $validated['duration_id'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'price_paid' => $duration->price,
                'status' => PeriodStatus::IN_PROGRESS,
            ]);

            // Update membership status to active
            // Todo: Should we update membership status only on creation?
            $this->membership->update([
                'status' => MembershipStatus::ACTIVE,
            ]);
            // Update member status to active
            $this->membership->member->update(['status' => MemberStatus::ACTIVE]);

            $flash = 'Membresía renovada exitosamente';
        }

        $this->closeModal();

        // Notify the Memberships and MembershipHistory components that a period has been added or edited
        $this->dispatch('period-saved', $flash);
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
