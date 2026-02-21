<?php

namespace App\Livewire\Prices;

use App\Enums\DurationUnit;
use App\Models\MembershipType;
use App\Models\PeriodType;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Livewire component for managing memberships prices.
 * This component handles the creation, editing, and deletion of membership types and period types.
 * Membership type is the type of membership (e.g. General, Student).
 * Period type is the duration of the membership (e.g. 1 month, 1 year) and its price.
 */
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
    public $period_type_name = '';

    #[Rule('required', message: 'La duración es obligatoria')]
    #[Rule('integer', message: 'La duración debe ser un número')]
    #[Rule('min:1', message: 'La duración debe ser mayor a 0')]
    public $period_type_duration_value = '';

    #[Rule('required', message: 'La unidad de tiempo es obligatoria')]
    #[Rule('in:day,week,month', message: 'Elige una unidad de tiempo válida')]
    public $period_type_duration_unit = '';

    #[Rule('required', message: 'El precio es obligatorio')]
    #[Rule('integer', message: 'El precio debe ser un número entero')]
    #[Rule('min:1', message: 'El precio debe ser mayor a 0')]
    public $period_type_price = '';

    // Modal states
    public $showMembershipTypeModal = false;
    public $showPeriodTypeModal = false;

    // Editing states
    public MembershipType|null $editingMembershipType = null;
    public PeriodType|null $editingPeriodType = null;
    /** Selected membership type for new period type */
    public MembershipType|null $selectedMembershipTypeForPeriod = null;

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
        $this->resetTypeForm();
        $this->resetValidation();
    }

    /**
     * Reset MembershipType form fields.
     */
    private function resetTypeForm()
    {
        $this->membership_type_name = '';
    }

    /**
     * Delete MembershipType.
     */
    public function deleteMembershipType(MembershipType $membershipType)
    {
        if ($membershipType->periodTypes->count() > 0) {
            session()->flash('error', 'No se puede eliminar este tipo de membresía');
            return;
        }

        $membershipType->delete();

        session()->flash('message', 'Tipo de membresía eliminado exitosamente');
    }

    /**
     * Show create PeriodType modal.
     */
    public function createPeriodTypeModal(MembershipType $membershipType)
    {
        $this->editingPeriodType = null;
        $this->selectedMembershipTypeForPeriod = $membershipType;
        $this->showPeriodTypeModal = true;
    }

    /**
     * Show edit PeriodType modal.
     */
    public function editPeriodTypeModal(PeriodType $periodType)
    {
        $this->editingPeriodType = $periodType;

        $this->period_type_name = $periodType->name;
        $this->period_type_duration_value = $periodType->duration_value;
        $this->period_type_duration_unit = $periodType->duration_unit->value;
        $this->period_type_price = $periodType->price;

        $this->showPeriodTypeModal = true;
    }

    /**
     * Save PeriodType (create or update).
     */
    public function savePeriodType()
    {
        $this->validate([
            'period_type_name' => 'required|max:255',
            'period_type_duration_value' => 'required|integer|min:1',
            'period_type_duration_unit' => 'required|in:day,week,month',
            'period_type_price' => 'required|integer|min:1',
        ]);

        $periodType = [
            'name' => $this->period_type_name,
            'duration_value' => $this->period_type_duration_value,
            'duration_unit' => DurationUnit::from($this->period_type_duration_unit),
            'duration_in_days' => DurationUnit::from($this->period_type_duration_unit)->toDays() * $this->period_type_duration_value,
            'price' => $this->period_type_price,
        ];

        if ($this->editingPeriodType) {
            $this->editingPeriodType->update($periodType);
            $flashMsg = 'Periodo actualizado exitosamente';
        } else {
            $this->selectedMembershipTypeForPeriod->periodTypes()->create($periodType);
            $flashMsg = 'Periodo creado exitosamente';
        }

        $this->closePeriodTypeModal();

        session()->flash('message', $flashMsg);
    }

    /**
     * Close PeriodType modal and reset form.
     */
    public function closePeriodTypeModal()
    {
        $this->showPeriodTypeModal = false;
        $this->editingPeriodType = null;
        $this->selectedMembershipTypeForPeriod = null;
        $this->resetPeriodTypeForm();
        $this->resetValidation();
    }

    /**
     * Reset PeriodType form fields.
     */
    private function resetPeriodTypeForm()
    {
        $this->period_type_name = '';
        $this->period_type_duration_value = '';
        $this->period_type_duration_unit = '';
        $this->period_type_price = '';
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
        $membershipTypes = MembershipType::with(['periodTypes'])->get();

        return view('livewire.prices.memberships', compact('membershipTypes'));
    }
}
