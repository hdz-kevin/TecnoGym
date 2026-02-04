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

    public function createModal()
    {
        $this->editingVisitType = null;
        $this->showModal = true;
    }

    public function editModal(VisitType $visitType)
    {
        $this->editingVisitType = $visitType;
        $this->name = $visitType->name;
        $this->price = $visitType->price;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingVisitType) {
            $this->editingVisitType->update([
                'name' => $this->name,
                'price' => $this->price,
            ]);
        } else {
            VisitType::create([
                'name' => $this->name,
                'price' => $this->price,
            ]);
        }

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function delete(VisitType $visitType)
    {
        if ($visitType->visits->count()) {
            return;
        }

        $visitType->delete();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->price = '';
    }

    public function render()
    {
        $visitTypes = VisitType::all();

        return view('livewire.visit-types', compact('visitTypes'));
    }
}
