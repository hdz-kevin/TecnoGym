<?php

namespace App\Livewire\Store;

use App\Models\Sale;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Sales extends Component
{
    use WithPagination, WithoutUrlPagination;

    /** Search by date */
    public string $search = '';

    /** Date filter: 'today', 'week', 'month', all */
    public string $dateFilter = 'today';

    /**
     * Set the date filter
     */
    public function setDateFilter(string $filter)
    {
        $this->dateFilter = $filter;
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Reset date filter when searching
     */
    public function updatedSearch()
    {
        $this->dateFilter = 'all';
        $this->resetPage();
    }

    /**
     * Get filtered sales with their product details
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    #[Computed]
    public function sales()
    {
        return Sale::query()
            ->withCount('productSales')
            ->when($this->dateFilter, function ($query) {
                match ($this->dateFilter) {
                    'today' => $query->whereDate('sold_at', Carbon::today()),
                    'week'  => $query->whereBetween('sold_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
                    'month' => $query->whereMonth('sold_at', Carbon::now()->month)
                                     ->whereYear('sold_at', Carbon::now()->year),
                    default => null,
                };
            })
            ->when($this->search, function ($query) {
                $query->whereDate('sold_at', $this->search);
            })
            ->orderBy('sold_at', 'desc')
            ->paginate(6);
    }

    /**
     * Get the total number of sales
     *
     * @return int
     */
    #[Computed]
    public function totalSales()
    {
        return Sale::count();
    }

    /**
     * Get today's sales count
     *
     * @return int
     */
    #[Computed]
    public function todaySales()
    {
        return Sale::whereDate('sold_at', Carbon::today())->count();
    }

    /**
     * Get this week's sales count
     *
     * @return int
     */
    #[Computed]
    public function weekSales()
    {
        return Sale::whereBetween('sold_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    }

    /**
     * Get this month's sales count
     *
     * @return int
     */
    #[Computed]
    public function monthSales()
    {
        return Sale::whereMonth('sold_at', Carbon::now()->month)
                    ->whereYear('sold_at', Carbon::now()->year)
                    ->count();
    }

    /**
     * Render the component
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.store.sales');
    }
}
