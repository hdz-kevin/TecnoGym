<?php

namespace App\Livewire;

use App\Models\Member;
use App\Enums\MemberGender;
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

    // Modal state
    public $showCreateModal = false;

    // Member properties
    public $name = '';
    public $gender = '';
    public $birth_date = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'gender' => 'required|in:M,F',
        'birth_date' => 'nullable|date',
    ];

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
        $members = Member::all()->take(9);

        return view('livewire.members', compact('members'));
    }
}
