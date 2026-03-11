<?php

namespace App\Livewire\Prices;

use App\Enums\DurationUnit;
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
    // Form fields for MembershipType
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('max:255', message: 'El nombre es muy largo')]
    public $membership_type_name = '';

    // Form fields for PeriodType
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('max:255', message: 'El nombre es muy largo')]
    public $period_duration_name = '';

    #[Rule('required', message: 'La cantidad es obligatoria')]
    #[Rule('integer', message: 'La cantidad debe ser un número')]
    #[Rule('min:1', message: 'La cantidad debe ser mayor a 0')]
    public $period_duration_value = '';

    #[Rule('required', message: 'La unidad de tiempo es obligatoria')]
    #[Rule('in:day,week,month', message: 'Elige una unidad de tiempo válida')]
    public $period_duration_unit = '';

    #[Rule('required', message: 'El precio es obligatorio')]
    #[Rule('integer', message: 'El precio debe ser un número entero')]
    #[Rule('min:1', message: 'El precio debe ser mayor a 0')]
    public $period_duration_price = '';

    // Modal states
    public $showMembershipTypeModal = false;
    public $showPeriodDurationModal = false;

    // Editing states
    public MembershipType|null $editingMembershipType = null;
    public PeriodDuration|null $editingPeriodDuration = null;
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
     * Show create PeriodDuration modal.
     */
    public function createPeriodDurationModal(MembershipType $membershipType)
    {
        $this->editingPeriodDuration = null;
        $this->selectedMembershipType = $membershipType;
        $this->showPeriodDurationModal = true;
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
     * Save PeriodDuration (create or update).
     */
    public function savePeriodDuration()
    {
        $this->validate([
            'period_duration_name' => 'required|max:255',
            'period_duration_value' => 'required|integer|min:1',
            'period_duration_unit' => 'required|in:day,week,month',
            'period_duration_price' => 'required|integer|min:1',
        ]);

        $periodDuration = [
            'name' => $this->period_duration_name,
            'duration_value' => $this->period_duration_value,
            'duration_unit' => DurationUnit::from($this->period_duration_unit),
            'price' => $this->period_duration_price,
        ];

        if ($this->editingPeriodDuration) {
            $this->editingPeriodDuration->update($periodDuration);
            $flashMsg = 'Duración actualizada exitosamente';
        } else {
            $this->selectedMembershipType->periodDurations()->create($periodDuration);
            $flashMsg = 'Duración creada exitosamente';
        }

        $this->closePeriodDurationModal();

        session()->flash('message', $flashMsg);
    }

    /**
     * Close PeriodDuration modal and reset form.
     */
    public function closePeriodDurationModal()
    {
        $this->showPeriodDurationModal = false;
        $this->editingPeriodDuration = null;
        $this->selectedMembershipType = null;
        $this->resetPeriodDurationForm();
        $this->resetValidation();
    }

    /**
     * Reset PeriodDuration form fields.
     */
    private function resetPeriodDurationForm()
    {
        $this->period_duration_name = '';
        $this->period_duration_value = '';
        $this->period_duration_unit = '';
        $this->period_duration_price = '';
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
