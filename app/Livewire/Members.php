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
    public $birth_date = null;

    // Birth date parts
    public $birth_day = '';
    public $birth_month = '';
    public $birth_year = '';

    // Modal state
    public $showModal = false;

    /** Updating member instance or null (no updating by default) */
    public Member|null $updatingMember = null;

    /**
     * Save a new member to the database.
     */
    public function saveMember()
    {
        // If at least one part of the date is set, construct the full date
        if ($this->birth_day || $this->birth_month || $this->birth_year) {
            $this->birth_date = sprintf('%04d-%02d-%02d', $this->birth_year, $this->birth_month, $this->birth_day);
        }

        $validated = $this->validate();

        if ($this->updatingMember) {
            $this->updatingMember->update($validated);
            $flashMessage = 'Socio actualizado exitosamente.';
        } else {
            Member::create([
                'name' => $this->name,
                'gender' => MemberGender::from($this->gender),
                'birth_date' => $this->birth_date,
            ]);
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
        $this->showModal = true;
    }

    /**
     * Show the update member modal.
     */
    public function updateMemberModal(Member $member)
    {
        $this->updatingMember = $member;
        $this->showModal = true;

        $this->name = $member->name;
        $this->gender = $member->gender;
        if ($member->birth_date) {
            $this->birth_day = $member->birth_date?->format('d') ?? '';
            $this->birth_month = $member->birth_date?->format('m') ?? '';
            $this->birth_year = $member->birth_date?->format('Y') ?? '';
        }
    }

    /**
     * Close the modal and reset form state.
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->updatingMember = null;
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
        $members = Member::all();

        return view('livewire.members', compact('members'));
    }
}
