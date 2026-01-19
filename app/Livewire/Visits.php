<?php

namespace App\Livewire;

use App\Models\Visit;
use App\Models\VisitType;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Visits extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Validate('required', message: 'Elige un tipo de visita')]
    #[Validate('exists:visit_types,id', message: 'Elige un tipo de visita válido')]
    public $visit_type_id;

    #[Validate('required', message: 'La fecha es obligatoria')]
    #[Validate('date', message: 'La fecha debe ser una fecha válida')]
    public $visit_date;

    #[Validate('required', message: 'La hora es obligatoria')]
    #[Validate('date_format:H:i', message: 'La hora debe ser una hora válida')]
    public $visit_time;

    #[Validate('required', message: 'El precio es obligatorio')]
    #[Validate('numeric', message: 'El precio debe ser un número')]
    public $price_paid;

    /** Visit date and time */
    public ?Carbon $visit_at = null;

    /** Create/edit visit modal state */
    public bool $showFormModal = false;

    /** Editing visit instance or null (no editing by default) */
    public ?Visit $editingVisit = null;

    /**
     * Get the visits ordered by visit_at desc
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    #[Computed]
    public function visits()
    {
        return Visit::with('visitType')
                    ->orderBy('visit_at', 'desc')
                    ->paginate(10);
    }

    /**
     * Cached visit types
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    #[Computed]
    public function visitTypes()
    {
        return VisitType::all();
    }

    /**
     * Open create visit modal and set defaults
     *
     * @return void
     */
    public function create()
    {
        // Set defaults
        $this->visit_at = Carbon::now();
        $this->visit_date = $this->visit_at->format('Y-m-d');
        $this->visit_time = $this->visit_at->format('H:i');

        // Set first visit type id and price
        if ($firstType = $this->visitTypes()->first()) {
            $this->visit_type_id = $firstType->id;
            $this->price_paid = $firstType->price;
        }

        $this->showFormModal = true;
    }

    /**
     * Open edit visit modal loading visit data
     *
     * @param Visit $visit
     * @return void
     */
    public function edit(Visit $visit)
    {
        $this->editingVisit = $visit;

        $this->visit_type_id = $visit->visit_type_id;
        $this->visit_date = $visit->visit_at->format('Y-m-d');
        $this->visit_time = $visit->visit_at->format('H:i');
        $this->price_paid = $visit->price_paid;

        $this->showFormModal = true;
    }

    /**
     * Update price when visit type changes
     *
     * @param VisitType $visitType
     * @return void
     */
    public function updatedVisitTypeId(VisitType $visitType)
    {
        $this->price_paid = $visitType->price;
    }

    /**
     * Save visit or update it if it's being edited
     *
     * @return void
     */
    public function save()
    {
        $this->validate();

        $dateTime = Carbon::parse($this->visit_date . ' ' . $this->visit_time);

        if ($this->editingVisit) {
            $this->editingVisit->update([
                'visit_type_id' => $this->visit_type_id,
                'visit_at' => $dateTime,
                'price_paid' => $this->price_paid,
            ]);
        } else {
            Visit::create([
                'visit_type_id' => $this->visit_type_id,
                'visit_at' => $dateTime,
                'price_paid' => $this->price_paid,
            ]);
        }

        $this->closeModal();
    }

    /**
     * Delete visit from database
     *
     * @param Visit $visit
     * @return void
     */
    public function delete(Visit $visit)
    {
        $visit->delete();
    }

    /**
     * Close the modal and reset the form
     *
     * @return void
     */
    public function closeModal()
    {
        $this->showFormModal = false;
        $this->editingVisit = null;
        $this->resetForm();
    }

    /**
     * Reset the form fields and validation
     *
     * @return void
     */
    private function resetForm()
    {
        $this->reset(['visit_type_id', 'visit_at', 'price_paid', 'visit_date', 'visit_time']);
        $this->resetValidation();
    }

    /** --> Statistics counters <-- */

    /**
     * Get the number of visits today
     *
     * @return int
     */
    #[Computed]
    public function today()
    {
        return Visit::whereDate('visit_at', Carbon::today())
                    ->count();
    }

    /**
     * Get the number of visits this week
     *
     * @return int
     */
    #[Computed]
    public function thisWeek()
    {
        return Visit::whereBetween('visit_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count();
    }

    /**
     * Get the number of visits this month
     *
     * @return int
     */
    #[Computed]
    public function thisMonth()
    {
        return Visit::whereBetween('visit_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->count();
    }

    /**
     * Get the total number of visits
     *
     * @return int
     */
    #[Computed]
    public function total()
    {
        return Visit::count();
    }

    /**
     * Render the component
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.visits');
    }
}
