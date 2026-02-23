<?php

namespace App\Livewire\Prices;

use App\Models\VisitType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * This component handles the creation, editing, and deletion of visit types.
 * A visit type contains the name and price.
 */
#[Layout('layouts.app')]
#[Title('Precios de Visitas')]
class Visits extends Component
{
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('max:255', 'El nombre es muy largo')]
    public $name = '';

    #[Rule('required', message: 'El precio es obligatorio')]
    #[Rule('integer', message: 'El precio debe ser un número entero')]
    public $price = '';

    public $showModal = false;
    public VisitType|null $editingVisitType = null;

    /**
     * Open create modal
     *
     * @return void
     */
    public function createModal()
    {
        $this->editingVisitType = null;
        $this->showModal = true;
    }

    /**
     * Open edit modal setting the visit type to edit
     *
     * @param VisitType $visitType
     * @return void
     */
    public function editModal(VisitType $visitType)
    {
        $this->editingVisitType = $visitType;
        $this->name = $visitType->name;
        $this->price = $visitType->price;
        $this->showModal = true;
    }

    /**
     * Save the visit type (create or update)
     *
     * @return void
     */
    public function save()
    {
        $validated = $this->validate();

        if ($this->editingVisitType) {
            $this->editingVisitType->update($validated);
            $flashMsg = 'Tipo de visita actualizado correctamente';
        } else {
            VisitType::create($validated);
            $flashMsg = 'Tipo de visita creado correctamente';
        }

        $this->closeModal();

        session()->flash('message', $flashMsg);
    }

    /**
     * Close the modal and reset the form and validation
     *
     * @return void
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    /**
     * Delete the visit type
     *
     * @param VisitType $visitType
     * @return void
     */
    public function delete(VisitType $visitType)
    {
        if ($visitType->visits->count() > 0) {
            session()->flash('error', 'No se puede eliminar el tipo de visita');
            return;
        }

        $visitType->delete();

        session()->flash('message', 'Tipo de visita eliminado correctamente');
    }

    /**
     * Reset the form fields
     *
     * @return void
     */
    private function resetForm()
    {
        $this->name = '';
        $this->price = '';
    }

    /**
     * Render the component
     *
     * @return void
     */
    public function render()
    {
        $visitTypes = VisitType::all();

        return view('livewire.prices.visits', compact('visitTypes'));
    }
}
