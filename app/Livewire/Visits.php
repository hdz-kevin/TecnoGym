<?php

namespace App\Livewire;

use App\Models\Visit;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Visits extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Validate('required', message: 'La fecha es obligatoria')]
    #[Validate('date', message: 'La fecha debe ser una fecha válida')]
    public $visit_date;

    #[Validate('required', message: 'La hora es obligatoria')]
    #[Validate('date_format:H:i', message: 'La hora debe ser una hora válida')]
    public $visit_time;

    #[Validate('required', message: 'El precio es obligatorio')]
    #[Validate('numeric', message: 'El precio debe ser un número')]
    public $price;

    /** Visit date and time */
    public ?Carbon $visit_at = null;

    /** Create/edit visit modal state */
    public bool $showFormModal = false;

    /** Editing visit instance or null (no editing by default) */
    public ?Visit $editingVisit = null;

    /**
     * Default price for new visits
     */
    public int $defaultPrice = 40;

    /** Date filter: 'today', 'week', 'month', 'all' */
    public string $dateFilter = 'today';

    /** Search by date */
    public string $search = '';

    /**
     * Set the date filter and reset pagination
     */
    public function setDateFilter(string $filter)
    {
        $this->dateFilter = $filter;
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Reset date filter when searching by date
     */
    public function updatedSearch()
    {
        $this->dateFilter = 'all';
        $this->resetPage();
    }

    /**
     * Get all visits filtered by date and ordered by visit_at desc
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function visits()
    {
        return Visit::query()
            ->when($this->dateFilter, function ($query) {
                match ($this->dateFilter) {
                    'today' => $query->whereDate('visit_at', Carbon::today()),
                    'week'  => $query->whereBetween('visit_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
                    'month' => $query->whereBetween('visit_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]),
                    default => null,
                };
            })
            ->when($this->search, function ($query) {
                $query->whereDate('visit_at', $this->search);
            })
            ->orderBy('visit_at', 'desc')
            ->paginate(6);
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
        $this->price = $this->defaultPrice;

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

        $this->visit_date = $visit->visit_at->format('Y-m-d');
        $this->visit_time = $visit->visit_at->format('H:i');
        $this->price = $visit->price;

        $this->showFormModal = true;
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
                'visit_at' => $dateTime,
                'price' => $this->price,
            ]);
            $flashMsg = 'Visita actualizada exitosamente';
        } else {
            Visit::create([
                'visit_at' => $dateTime,
                'price' => $this->price,
            ]);
            $flashMsg = 'Visita registrada exitosamente';
        }

        $this->closeModal();

        session()->flash('message', $flashMsg);
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

        session()->flash('message', 'Visita eliminada exitosamente');
    }

    /**
     * Close the modal and reset the form
     *
     * @return void
     */
    public function closeModal()
    {
        $this->visit_at = null;
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
        $this->reset(['visit_date', 'visit_time', 'price']);
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
