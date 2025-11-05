<?php

namespace App\Livewire;

use App\Models\PlanType;
use App\Models\Plan;
use App\Enums\DurationUnit;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Planes')]
class Plans extends Component
{
    // Modal states
    public $showTypeModal = false;
    public $showPlanModal = false;

    // Editing states
    public PlanType|null $editingType = null;
    public Plan|null $editingPlan = null;
    public PlanType|null $selectedTypeForPlan = null;

    // Form fields for PlanType
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255')]
    public $typeName = '';

    // Form fields for Plan
    #[Rule('required', message: 'El nombre del plan es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255')]
    public $planName = '';

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
     * Create new plan type
     */
    public function createType()
    {
        $this->editingType = null;
        $this->showTypeModal = true;
        $this->resetTypeForm();
    }

    /**
     * Edit existing plan type
     */
    public function editType(PlanType $type)
    {
        $this->editingType = $type;
        $this->typeName = $type->name;
        $this->showTypeModal = true;
    }

    /**
     * Save plan type (create or update)
     */
    public function saveType()
    {
        $this->validate([
            'typeName' => 'required|string|max:255'
        ]);

        if ($this->editingType) {
            $this->editingType->update(['name' => $this->typeName]);
            $message = 'Tipo de plan actualizado exitosamente.';
        } else {
            PlanType::create(['name' => $this->typeName]);
            $message = 'Tipo de plan creado exitosamente.';
        }

        $this->closeTypeModal();
        session()->flash('message', $message);
    }

    /**
     * Create new plan for a plan type
     */
    public function createPlan(PlanType $planType)
    {
        $this->editingPlan = null;
        $this->selectedTypeForPlan = $planType;
        $this->showPlanModal = true;
        $this->resetPlanForm();
    }

    /**
     * Edit existing plan
     */
    public function editPlan(Plan $plan)
    {
        $this->editingPlan = $plan;
        $this->selectedTypeForPlan = $plan->planType;
        $this->planName = $plan->name;
        $this->durationValue = $plan->duration_value;
        $this->durationUnit = $plan->duration_unit->value;
        $this->price = $plan->price;
        $this->showPlanModal = true;
    }

    /**
     * Save plan (create or update)
     */
    public function savePlan()
    {
        $this->validate([
            'planName' => 'required|string|max:255',
            'durationValue' => 'required|integer|min:1',
            'durationUnit' => 'required',
            'price' => 'required|integer|min:1',
        ]);

        $planData = [
            'name' => $this->planName,
            'duration_value' => $this->durationValue,
            'duration_unit' => DurationUnit::from($this->durationUnit),
            'price' => $this->price,
        ];

        if ($this->editingPlan) {
            $this->editingPlan->update($planData);
            $message = 'Plan actualizado exitosamente.';
        } else {
            $this->selectedTypeForPlan->plans()->create($planData);
            $message = 'Plan creado exitosamente.';
        }

        $this->closePlanModal();
        session()->flash('message', $message);
    }

    /**
     * Delete plan type
     */
    public function deleteType(PlanType $type)
    {
        // Check if type has plans
        if ($type->plans()->count() > 0) {
            session()->flash('error', 'No se puede eliminar un tipo que tiene planes asociados.');
            return;
        }

        $type->delete();
        session()->flash('message', 'Tipo de plan eliminado exitosamente.');
    }

    /**
     * Delete plan
     */
    public function deletePlan(Plan $plan)
    {
        $plan->delete();
        session()->flash('message', 'Plan eliminado exitosamente.');
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
     * Close plan modal and reset form
     */
    public function closePlanModal()
    {
        $this->showPlanModal = false;
        $this->editingPlan = null;
        $this->selectedTypeForPlan = null;
        $this->resetPlanForm();
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
     * Reset plan form fields
     */
    private function resetPlanForm()
    {
        $this->planName = '';
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
