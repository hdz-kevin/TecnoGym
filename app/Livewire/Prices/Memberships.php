<?php

namespace App\Livewire\Prices;

use App\Enums\DurationUnit;
use App\Models\Duration;
use App\Models\MembershipType;
use App\Models\PeriodDuration;
use App\Models\PeriodType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Livewire component for managing memberships prices.
 * This component handles the creation, editing, and deletion of membership types and period types.
 * Membership type is the type of membership (e.g. General, Student).
 * Period type is the duration of the membership (e.g. 1 month, 1 year) and its price.
 */
#[Layout('layouts.app')]
#[Title('Precios de Membresías')]
class Memberships extends Component
{
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('max:255', message: 'El nombre es muy largo')]
    public $membership_type_name = '';

    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('max:255', message: 'El nombre es muy largo')]
    public $duration_name = '';

    #[Rule('required', message: 'La cantidad es obligatoria')]
    #[Rule('integer', message: 'La cantidad debe ser un número')]
    #[Rule('min:1', message: 'La cantidad debe ser mayor a 0')]
    public $duration_amount = '';

    #[Rule('required', message: 'La unidad de tiempo es obligatoria')]
    #[Rule('in:day,week,month', message: 'Elige una unidad de tiempo válida')]
    public $duration_unit = '';

    #[Rule('required', message: 'El precio es obligatorio')]
    #[Rule('integer', message: 'El precio debe ser un número entero')]
    #[Rule('min:1', message: 'El precio debe ser mayor a 0')]
    public $duration_price = '';

    // Modal states
    public $showMembershipTypeModal = false;
    public $showDurationModal = false;

    // Editing states
    public MembershipType|null $editingMembershipType = null;
    public Duration|null $editingDuration = null;
    /** Selected membership type for new period duration */
    public MembershipType|null $selectedMembershipType = null;

    /**
     * Show create MembershipType modal
     */
    public function createMembershipTypeModal()
    {
        $this->editingMembershipType = null;
        $this->showMembershipTypeModal = true;
    }

    /**
     * Show edit MembershipType modal.
     */
    public function editMembershipTypeModal(MembershipType $membershipType)
    {
        $this->editingMembershipType = $membershipType;
        $this->membership_type_name = $membershipType->name;
        $this->showMembershipTypeModal = true;
    }

    /**
     * Save MembershipType (create or update)
     */
    public function saveMembershipType()
    {
        $this->validate([
            'membership_type_name' => 'required|max:255',
        ]);

        if ($this->editingMembershipType) {
            $this->editingMembershipType->update(['name' => $this->membership_type_name]);
            $flashMsg = 'Tipo de membresía actualizado exitosamente';
        } else {
            MembershipType::create(['name' => $this->membership_type_name]);
            $flashMsg = 'Tipo de membresía creado exitosamente';
        }

        $this->closeMembershipTypeModal();

        session()->flash('message', $flashMsg);
    }

    /**
     * Close MembershipType modal and reset form.
     */
    public function closeMembershipTypeModal()
    {
        $this->showMembershipTypeModal = false;
        $this->editingMembershipType = null;
        $this->resetMembershipTypeForm();
        $this->resetValidation();
    }

    /**
     * Reset MembershipType form fields.
     */
    private function resetMembershipTypeForm()
    {
        $this->membership_type_name = '';
    }

    /**
     * Delete MembershipType.
     */
    public function deleteMembershipType(MembershipType $membershipType)
    {
        if ($membershipType->durations->count() > 0) {
            session()->flash('error', 'No se puede eliminar este tipo de membresía');
            return;
        }

        $membershipType->delete();

        session()->flash('message', 'Tipo de membresía eliminado exitosamente');
    }

    /**
     * Show create Duration modal.
     */
    public function createDurationModal(MembershipType $membershipType)
    {
        $this->editingDuration = null;
        $this->selectedMembershipType = $membershipType;
        $this->showDurationModal = true;
    }

    /**
     * Show edit PeriodType modal.
     */
    public function editPeriodDurationModal(PeriodDuration $periodDuration)
    {
        $this->editingPeriodDuration = $periodDuration;

        $this->period_duration_name = $periodDuration->name;
        $this->period_duration_value = $periodDuration->duration_value;
        $this->period_duration_unit = $periodDuration->duration_unit->value;
        $this->period_duration_price = $periodDuration->price;

        $this->showPeriodDurationModal = true;
    }

    /**
     * Save Duration (create or update).
     */
    public function saveDuration()
    {
        $validated = $this->validate([
            'duration_name' => 'required|max:255',
            'duration_amount' => 'required|integer|min:1',
            'duration_unit' => 'required|in:day,week,month',
            'duration_price' => 'required|integer|min:1',
        ]);

        $duration = [
            'name' => $validated['duration_name'],
            'amount' => $validated['duration_amount'],
            'unit' => DurationUnit::from($validated['duration_unit']),
            'price' => $validated['duration_price'],
        ];

        if ($this->editingDuration) {
            $this->editingDuration->update($duration);
            $flashMsg = 'Duración actualizada exitosamente';
        } else {
            $this->selectedMembershipType->durations()->create($duration);
            $flashMsg = 'Duración creada exitosamente';
        }

        $this->closeDurationModal();

        session()->flash('message', $flashMsg);
    }

    /**
     * Close PeriodDuration modal and reset form.
     */
    public function closeDurationModal()
    {
        $this->showDurationModal = false;
        $this->editingDuration = null;
        $this->selectedMembershipType = null;
        $this->resetPeriodDurationForm();
        $this->resetValidation();
    }

    /**
     * Reset PeriodDuration form fields.
     */
    private function resetPeriodDurationForm()
    {
        $this->duration_name = '';
        $this->duration_amount = '';
        $this->duration_unit = '';
        $this->duration_price = '';
    }

    /**
     * Delete PeriodType.
     */
    public function deletePeriodType(PeriodType $periodType)
    {
        if ($periodType->periods->count() > 0) {
            session()->flash('error', 'No se puede eliminar este periodo');
            return;
        }

        $periodType->delete();

        session()->flash('message', 'Periodo eliminado exitosamente');
    }

    /**
     * Render the component.
     */
    public function render()
    {
        $membershipTypes = MembershipType::with(['durations.periods'])->get();

        return view('livewire.prices.memberships', compact('membershipTypes'));
    }
}
