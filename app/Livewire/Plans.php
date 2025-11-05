<?php

namespace App\Livewire;

use App\Models\MembershipType;
use App\Models\Period;
use App\Enums\DurationUnit;
use App\Models\PlanType;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Tipos de Membresía')]
class Plans extends Component
{
    // Modal states
    public $showTypeModal = false;
    public $showPeriodModal = false;

    // Editing states
    public MembershipType|null $editingType = null;
    public Period|null $editingPeriod = null;
    public MembershipType|null $selectedTypeForPeriod = null;

    // Form fields for MembershipType
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255')]
    public $typeName = '';

    // Form fields for MembershipPeriod
    #[Rule('required', message: 'El nombre del período es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255')]
    public $periodName = '';

    #[Rule('required', message: 'La duración es obligatoria')]
    #[Rule('integer', message: 'La duración debe ser un número')]
    #[Rule('min:1', message: 'La duración debe ser mayor a 0')]
    public $durationValue = '';

    #[Rule('required', message: 'La unidad de tiempo es obligatoria')]
    public $durationUnit = '';

    #[Rule('required', message: 'El precio es obligatorio')]
    #[Rule('integer', message: 'El precio debe ser un número entero')]
    #[Rule('min:1', message: 'El precio debe ser mayor a 0')]
    public $price = '';

    /**
     * Create new membership type
     */
    public function createType()
    {
        $this->editingType = null;
        $this->showTypeModal = true;
        $this->resetTypeForm();
    }

    /**
     * Edit existing membership type
     */
    public function editType(MembershipType $type)
    {
        $this->editingType = $type;
        $this->typeName = $type->name;
        $this->showTypeModal = true;
    }

    /**
     * Save membership type (create or update)
     */
    public function saveType()
    {
        $this->validate([
            'typeName' => 'required|string|max:255'
        ]);

        if ($this->editingType) {
            $this->editingType->update(['name' => $this->typeName]);
            $message = 'Tipo de membresía actualizado exitosamente.';
        } else {
            MembershipType::create(['name' => $this->typeName]);
            $message = 'Tipo de membresía creado exitosamente.';
        }

        $this->closeTypeModal();
        session()->flash('message', $message);
    }

    /**
     * Create new period for a membership type
     */
    public function createPeriod(MembershipType $membershipType)
    {
        $this->editingPeriod = null;
        $this->selectedTypeForPeriod = $membershipType;
        $this->showPeriodModal = true;
        $this->resetPeriodForm();
    }

    /**
     * Edit existing period
     */
    public function editPeriod(Period $period)
    {
        $this->editingPeriod = $period;
        $this->selectedTypeForPeriod = $period->membershipType;
        $this->periodName = $period->name;
        $this->durationValue = $period->duration_value;
        $this->durationUnit = $period->duration_unit->value;
        $this->price = $period->price;
        $this->showPeriodModal = true;
    }

    /**
     * Save period (create or update)
     */
    public function savePeriod()
    {
        $this->validate([
            'periodName' => 'required|string|max:255',
            'durationValue' => 'required|integer|min:1',
            'durationUnit' => 'required',
            'price' => 'required|integer|min:1',
        ]);

        $periodData = [
            'name' => $this->periodName,
            'duration_value' => $this->durationValue,
            'duration_unit' => DurationUnit::from($this->durationUnit),
            'price' => $this->price,
        ];

        if ($this->editingPeriod) {
            $this->editingPeriod->update($periodData);
            $message = 'Período actualizado exitosamente.';
        } else {
            $this->selectedTypeForPeriod->periods()->create($periodData);
            $message = 'Período creado exitosamente.';
        }

        $this->closePeriodModal();
        session()->flash('message', $message);
    }

    /**
     * Delete membership type
     */
    public function deleteType(MembershipType $type)
    {
        // Check if type has periods
        if ($type->periods()->count() > 0) {
            session()->flash('error', 'No se puede eliminar un tipo que tiene períodos asociados.');
            return;
        }

        $type->delete();
        session()->flash('message', 'Tipo de membresía eliminado exitosamente.');
    }

    /**
     * Delete period
     */
    public function deletePeriod(Period $period)
    {
        $period->delete();
        session()->flash('message', 'Período eliminado exitosamente.');
    }

    /**
     * Close type modal and reset form
     */
    public function closeTypeModal()
    {
        $this->showTypeModal = false;
        $this->editingType = null;
        $this->resetTypeForm();
        $this->resetValidation();
    }

    /**
     * Close period modal and reset form
     */
    public function closePeriodModal()
    {
        $this->showPeriodModal = false;
        $this->editingPeriod = null;
        $this->selectedTypeForPeriod = null;
        $this->resetPeriodForm();
        $this->resetValidation();
    }

    /**
     * Reset type form fields
     */
    private function resetTypeForm()
    {
        $this->typeName = '';
    }

    /**
     * Reset period form fields
     */
    private function resetPeriodForm()
    {
        $this->periodName = '';
        $this->durationValue = '';
        $this->durationUnit = '';
        $this->price = '';
    }

    public function render()
    {
        $planTypes = PlanType::with('plans')->get();

        return view('livewire.plans', compact('planTypes'));
    }
}
