<?php

namespace App\Livewire\Prices;

use App\Models\PlanType;
use App\Models\Plan;
use App\Enums\DurationUnit;
use App\Models\MembershipType;
use App\Models\PeriodType;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Precios de Membresías')]
class Memberships extends Component
{
    // Form fields for PlanType
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255')]
    public $membership_type_name = '';

    // Form fields for Plan
    #[Rule('required', message: 'El nombre del plan es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255')]
    public $period_type_name = '';

    #[Rule('required', message: 'La duración es obligatoria')]
    #[Rule('integer', message: 'La duración debe ser un número')]
    #[Rule('min:1', message: 'La duración debe ser mayor a 0')]
    public $period_type_duration_value = '';

    #[Rule('required', message: 'La unidad de tiempo es obligatoria')]
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
    public MembershipType|null $selectedMembershipTypeForPeriod = null;

    /**
     * Show the create plan type modal.
     */
    public function createMembershipTypeModal()
    {
        $this->editingMembershipType = null;
        $this->showMembershipTypeModal = true;
    }

    /**
     * Show the edit plan type modal.
     */
    public function editMembershipTypeModal(MembershipType $membershipType)
    {
        $this->editingMembershipType = $membershipType;
        $this->membership_type_name = $membershipType->name;
        $this->showMembershipTypeModal = true;
    }

    /**
     * Save plan type (create or update)
     */
    public function saveMembershipType()
    {
        $this->validate([
            'membership_type_name' => 'required|string|max:255',
        ]);

        if ($this->editingMembershipType) {
            $this->editingMembershipType->update(['name' => $this->membership_type_name]);
            $flashMsg = 'Tipo de membresía actualizado exitosamente';
        } else {
            MembershipType::create(['name' => $this->membership_type_name]);
            $flashMsg = 'Tipo de membresía creado exitosamente';
        }

        $this->closeMembershipTypeModal();

        $this->dispatch('notify-changes', $flashMsg);
    }

    /**
     * Close type modal and reset form.
     */
    public function closeMembershipTypeModal()
    {
        $this->showMembershipTypeModal = false;
        $this->editingMembershipType = null;
        $this->resetTypeForm();
        $this->resetValidation();
    }

    /**
     * Reset type form fields.
     */
    private function resetTypeForm()
    {
        $this->membership_type_name = '';
    }

    /**
     * Delete plan type.
     */
    public function deleteMembershipType(MembershipType $membershipType)
    {
        if ($membershipType->periodTypes->count() > 0) {
            $this->dispatch('notify-changes', 'No puedes eliminar este tipo de membresía', 'error');
            return;
        }

        $membershipType->delete();

        $this->dispatch('notify-changes', 'Tipo de membresía eliminado exitosamente');
    }

    /**
     * Show create plan modal.
     */
    public function createPeriodTypeModal(MembershipType $membershipType)
    {
        $this->editingPeriodType = null;
        $this->selectedMembershipTypeForPeriod = $membershipType;
        $this->showPeriodTypeModal = true;
    }

    /**
     * Show edit plan modal.
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
     * Save plan (create or update).
     */
    public function savePeriodType()
    {
        $this->validate([
            'period_type_name' => 'required|string|max:255',
            'period_type_duration_value' => 'required|integer|min:1',
            'period_type_duration_unit' => 'required',
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
            $flashMsg = 'Plan actualizado exitosamente';
        } else {
            $this->selectedMembershipTypeForPeriod->periodTypes()->create($periodType);
            $flashMsg = 'Plan creado exitosamente';
        }

        $this->closePeriodTypeModal();

        $this->dispatch('notify-changes', $flashMsg);
    }

    /**
     * Close plan modal and reset form.
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
     * Reset plan form fields.
     */
    private function resetPeriodTypeForm()
    {
        $this->period_type_name = '';
        $this->period_type_duration_value = '';
        $this->period_type_duration_unit = '';
        $this->period_type_price = '';
    }

    /**
     * Delete plan.
     */
    public function deletePeriodType(PeriodType $periodType)
    {
        if ($periodType->periods->count() > 0) {
            $this->dispatch('notify-changes', 'No puedes eliminar este tipo de periodo', 'error');
            return;
        }

        $periodType->delete();

        $this->dispatch('notify-changes', 'Tipo de periodo eliminado exitosamente');
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
