<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Members extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $membership = '';

    public function createMember()
    {
        // Lógica para crear nuevo miembro
        session()->flash('message', 'Redirigiendo a crear nuevo socio...');
    }

    public function assignMembership()
    {
        // Lógica para asignar membresía
        session()->flash('message', 'Abriendo modal de asignación de membresía...');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.members');
    }
}
