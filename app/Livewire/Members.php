<?php

namespace App\Livewire;

use App\Models\Member;
use App\Enums\MemberGender;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Socios')]
class Members extends Component
{
    use WithPagination, WithFileUploads;

    // Properties
    #[Rule('required', message: 'El nombre es obligatorio')]
    #[Rule('string')]
    #[Rule('max:255', message: 'El nombre es muy largo')]
    public $name = '';

    #[Rule('required', message: 'Elige un genero')]
    #[Rule('in:male,female', message: 'Elige un genero válido')]
    public $gender = '';

    #[Rule('nullable')]
    #[Rule('date', message: 'La fecha no es válida')]
    public $birth_date = null;

    // Birth date parts
    public $birth_day = '';
    public $birth_month = '';
    public $birth_year = '';

    #[Rule('nullable')]
    #[Rule('image', message: 'El archivo debe ser una imagen')]
    #[Rule('max:2048', message: 'La imagen no debe superar los 2MB')]
    public $photo = null;

    // Modal state
    public $showFormModal = false;

    /** Editing member instance or null (no editing by default) */
    public Member|null $editingMember = null;

    /**
     * Save a new member or update an existing one.
     */
    public function saveMember()
    {
        // If at least one part of the date is set, construct the full date
        if ($this->birth_day || $this->birth_month || $this->birth_year) {
            $this->birth_date = sprintf('%04d-%02d-%02d', $this->birth_year, $this->birth_month, $this->birth_day);
        }

        $validated = $this->validate();

        if ($this->photo) {
            $photoPath = $this->photo->store('member-photos', 'public');
            $validated['photo'] = $photoPath;
        }

        if ($this->editingMember) {
            $this->editingMember->update($validated);
            $flashMessage = 'Socio actualizado exitosamente.';
        } else {
            Member::create($validated);
            $flashMessage = 'Socio creado exitosamente.';
        }

        $this->closeModal();
        session()->flash('message', $flashMessage);
    }

    /**
     * Show the create member modal.
     */
    public function createMemberModal()
    {
        $this->editingMember = null;
        $this->showFormModal = true;
    }

    /**
     * Show the edit member modal.
     */
    public function editMemberModal(Member $member)
    {
        $this->editingMember = $member;

        $this->name = $member->name;
        $this->gender = $member->gender;
        if ($member->birth_date) {
            $this->birth_day = $member->birth_date?->format('d') ?? '';
            $this->birth_month = $member->birth_date?->format('m') ?? '';
            $this->birth_year = $member->birth_date?->format('Y') ?? '';
        }

        $this->showFormModal = true;
    }

    /**
     * Close the modal and reset form state.
     */
    public function closeModal()
    {
        $this->showFormModal = false;
        $this->editingMember = null;
        $this->resetForm();
        $this->resetValidation();
    }

    /**
     * Reset the form fields to their default state.
     */
    private function resetForm()
    {
        $this->name = '';
        $this->gender = '';
        $this->birth_date = null;
        $this->birth_day = '';
        $this->birth_month = '';
        $this->birth_year = '';
    }

    public function render()
    {
        $members = Member::with('memberships')->get();

        return view('livewire.members', compact('members'));
    }
}
