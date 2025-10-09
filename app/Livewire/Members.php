<?php

namespace App\Livewire;

use App\Models\Member;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Socios')]
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
        $members = Member::all()->take(9);

        return view('livewire.members', compact('members'));
    }
}
