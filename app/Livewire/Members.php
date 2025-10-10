<?php

namespace App\Livewire;

use App\Models\Member;
use App\Enums\MemberGender;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Socios')]
class Members extends Component
{
    use WithPagination;

    // Properties
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255', message: 'El nombre es muy largo')]
    public $name = '';

    #[Rule('required', message: 'Elige un genero')]
    #[Rule('in:M,F', message: 'Elige un genero válido')]
    public $gender = '';

    #[Rule('nullable')]
    #[Rule('date', message: 'Formato de fecha invalido')]
    public $birth_date = '';

    // Modal state
    public $showCreateModal = false;

    public $search = '';
    public $status = '';
    public $membership = '';

    public function createMember()
    {
        $this->showCreateModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function saveMember()
    {
        $this->birth_date = $this->birth_date ?: null;
        $this->validate();

        Member::create([
            'name' => $this->name,
            'gender' => MemberGender::from($this->gender),
            'birth_date' => $this->birth_date,
        ]);

        $this->closeModal();
        session()->flash('message', 'Socio creado exitosamente.');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->gender = '';
        $this->birth_date = '';
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
        $members = Member::all();

        return view('livewire.members', compact('members'));
    }
}
