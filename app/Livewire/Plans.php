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
    // Form fields for PlanType
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255')]
    public $type_name = '';

    // Form fields for Plan
    #[Rule('required', message: 'El nombre del plan es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255')]
    public $plan_name = '';

    #[Rule('required', message: 'La duración es obligatoria')]
    #[Rule('integer', message: 'La duración debe ser un número')]
    #[Rule('min:1', message: 'La duración debe ser mayor a 0')]
    public $duration_value = '';

    #[Rule('required', message: 'La unidad de tiempo es obligatoria')]
    public $duration_unit = '';

    #[Rule('required', message: 'El precio es obligatorio')]
    #[Rule('integer', message: 'El precio debe ser un número entero')]
    #[Rule('min:1', message: 'El precio debe ser mayor a 0')]
    public $price = '';

    // Modal states
    public $showTypeModal = false;
    public $showPlanModal = false;

    // Editing states
    public PlanType|null $editingType = null;
    public Plan|null $editingPlan = null;
    public PlanType|null $selectedTypeForPlan = null;

    /**
     * Show the create plan type modal.
     */
    public function createTypeModal()
    {
        $this->editingType = null;
        $this->showTypeModal = true;
    }

    /**
     * Show the edit plan type modal.
     */
    public function editTypeModal(PlanType $type)
    {
        $this->editingType = $type;
        $this->type_name = $type->name;
        $this->showTypeModal = true;
    }

    /**
     * Save plan type (create or update)
     */
    public function saveType()
    {
        $this->validate([
            'type_name' => 'required|string|max:255',
        ]);

        if ($this->editingType) {
            $this->editingType->update(['name' => $this->type_name]);
        } else {
            PlanType::create(['name' => $this->type_name]);
        }

        $this->closeTypeModal();
    }

    /**
     * Close type modal and reset form.
     */
    public function closeTypeModal()
    {
        $this->showTypeModal = false;
        $this->editingType = null;
        $this->resetTypeForm();
        $this->resetValidation();
    }

    /**
     * Reset type form fields.
     */
    private function resetTypeForm()
    {
        $this->type_name = '';
    }

    /**
     * Delete plan type.
     */
    public function deleteType(PlanType $type)
    {
        // Check if type has plans
        if ($type->plans->count() > 0) {
            session()->flash('error', 'No se puede eliminar un tipo que tiene planes asociados.');
            return;
        }

        $type->delete();
        session()->flash('message', 'Tipo de plan eliminado exitosamente.');
    }

    /**
     * Show create plan modal.
     */
    public function createPlanModal(PlanType $planType)
    {
        $this->editingPlan = null;
        $this->selectedTypeForPlan = $planType;
        $this->showPlanModal = true;
    }

    /**
     * Show edit plan modal.
     */
    public function editPlanModal(Plan $plan)
    {
        $this->editingPlan = $plan;

        $this->plan_name = $plan->name;
        $this->duration_value = $plan->duration_value;
        $this->duration_unit = $plan->duration_unit->value;
        $this->price = $plan->price;

        $this->showPlanModal = true;
    }

    /**
     * Save plan (create or update).
     */
    public function savePlan()
    {
        $this->validate([
            'plan_name' => 'required|string|max:255',
            'duration_value' => 'required|integer|min:1',
            'duration_unit' => 'required',
            'price' => 'required|integer|min:1',
        ]);

        $planData = [
            'name' => $this->plan_name,
            'duration_value' => $this->duration_value,
            'duration_unit' => DurationUnit::from($this->duration_unit),
            'duration_in_days' => DurationUnit::from($this->duration_unit)->toDays() * $this->duration_value,
            'price' => $this->price,
        ];

        if ($this->editingPlan) {
            $this->editingPlan->update($planData);
        } else {
            $this->selectedTypeForPlan->plans()->create($planData);
        }

        $this->closePlanModal();
    }

    /**
     * Close plan modal and reset form.
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
     * Reset plan form fields.
     */
    private function resetPlanForm()
    {
        $this->plan_name = '';
        $this->duration_value = '';
        $this->duration_unit = '';
        $this->price = '';
    }

    /**
     * Delete plan.
     */
    public function deletePlan(Plan $plan)
    {
        if ($plan->memberships->count() > 0) {
            session()->flash('error', 'No se puede eliminar un plan que tiene membresías asociadas.');
            return;
        }

        $plan->delete();
        session()->flash('message', 'Plan eliminado exitosamente.');
    }

    public function render()
    {
        $planTypes = PlanType::with(['plans.memberships'])->get();

        return view('livewire.plans', compact('planTypes'));
    }
}
