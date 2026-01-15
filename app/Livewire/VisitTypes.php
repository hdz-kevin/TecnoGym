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
    #[Rule('min:0', message: 'El precio debe ser mayor o igual a 0')]
    public $price = '';

    public $showModal = false;
    public VisitType|null $editingVisitType = null;

    public function createModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->editingVisitType = null;
        $this->showModal = true;
    }

    public function editModal(VisitType $visitType)
    {
        $this->resetValidation();
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
    }

    public function delete(VisitType $visitType)
    {
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
