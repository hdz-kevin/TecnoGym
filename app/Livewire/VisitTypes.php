<?php

namespace App\Livewire;

use App\Models\VisitType;
use Livewire\Attributes\Rule;
use Livewire\Component;

class VisitTypes extends Component
{
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255')]
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
        $this->validate();

        if ($this->editingVisitType) {
            $this->editingVisitType->update([
                'name' => $this->name,
                'price' => $this->price,
            ]);
            $flashMsg = 'Tipo de visita actualizado correctamente';
        } else {
            VisitType::create([
                'name' => $this->name,
                'price' => $this->price,
            ]);
            $flashMsg = 'Tipo de visita creado correctamente';
        }

        $this->closeModal();

        $this->dispatch('notify-changes', $flashMsg);
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
            $this->dispatch('notify-changes', 'No se puede eliminar el tipo de visita', 'error');
            return;
        }

        $visitType->delete();

        $this->dispatch('notify-changes', 'Tipo de visita eliminado correctamente');
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

        return view('livewire.visit-types', compact('visitTypes'));
    }
}
