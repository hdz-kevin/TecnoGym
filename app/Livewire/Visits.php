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
    public ?Carbon $visit_at;

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

    public function create()
    {
        $this->resetForm();
        $this->showFormModal = true;

        // Set defaults
        $this->visit_at = Carbon::now();
        $this->visit_date = $this->visit_at->format('Y-m-d');
        $this->visit_time = $this->visit_at->format('H:i');

        // Default to first visit type if exists
        $firstType = $this->visitTypes()->first();
        if ($firstType) {
            $this->visit_type_id = $firstType->id;
            $this->price_paid = $firstType->price;
        }
    }

    public function edit(Visit $visit)
    {
        $this->resetForm();
        $this->editingVisit = $visit;

        $this->visit_type_id = $visit->visit_type_id;
        $this->price_paid = $visit->price_paid;

        $visitAt = Carbon::parse($visit->visit_at);
        $this->visit_date = $visitAt->format('Y-m-d');
        $this->visit_time = $visitAt->format('H:i');

        $this->showFormModal = true;
    }

    // Update price when visit type changes
    public function updatedVisitTypeId($value)
    {
        $type = $this->visitTypes()->firstWhere('id', $value);
        if ($type) {
            $this->price_paid = $type->price;
        }
    }

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

            session()->flash('message', 'Visita actualizada correctamente.');
        } else {
            Visit::create([
                'visit_type_id' => $this->visit_type_id,
                'visit_at' => $dateTime,
                'price_paid' => $this->price_paid,
            ]);

            session()->flash('message', 'Visita registrada correctamente.');
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $visit = Visit::find($id);

        if ($visit) {
            $visit->delete();
            session()->flash('message', 'Visita eliminada correctamente.');
        }
    }

    public function closeModal()
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingVisit = null;
        $this->visit_type_id = null;
        $this->visit_at = null;
        $this->price_paid = null;
        $this->visit_date = null;
        $this->visit_time = null;
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

    public function render()
    {
        return view('livewire.visits');
    }
}
