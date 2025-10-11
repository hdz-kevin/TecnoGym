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
    #[Rule('date', message: 'La fecha no es válida')]
    public $birth_date = '';

    // Birth date parts
    public $birth_day = '';
    public $birth_month = '';
    public $birth_year = '';

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

    /**
     * Save a new member to the database.
     */
    public function saveMember()
    {
        // If at least one part of the date is set, construct the full date
        if ($this->birth_day || $this->birth_month || $this->birth_year) {
            $this->birth_date = sprintf('%04d-%02d-%02d', $this->birth_year, $this->birth_month, $this->birth_day);
        } else {
            $this->birth_date = null;
        }

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
