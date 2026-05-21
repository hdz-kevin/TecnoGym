<?php

namespace App\Livewire\Store;

use App\Models\Sale;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Sales extends Component
{
    use WithPagination, WithoutUrlPagination;

    /** Date filter: 'today', 'week', 'month', all */
    public string $dateFilter = 'today';

    /** Search by date */
    public string $search = '';

    /**
     * Open create sale modal
     */
    public function createSale()
    {
        $this->dispatch('open-create-sale-modal');
    }

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
            ->withSum('productSales', 'quantity')
            ->when($this->dateFilter, function ($query) {
                match ($this->dateFilter) {
                    'today' => $query->whereDate('sold_at', Carbon::today()),
                    'week'  => $query->whereBetween('sold_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
                    'month' => $query->whereBetween('sold_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]),
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
    public function total()
    {
        return Sale::count();
    }

    /**
     * Get today's sales count
     *
     * @return int
     */
    #[Computed]
    public function today()
    {
        return Sale::whereDate('sold_at', Carbon::today())->count();
    }

    /**
     * Get this week's sales count
     *
     * @return int
     */
    #[Computed]
    public function thisWeek()
    {
        return Sale::whereBetween('sold_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    }

    /**
     * Get this month's sales count
     *
     * @return int
     */
    #[Computed]
    public function thisMonth()
    {
        return Sale::whereMonth('sold_at', Carbon::now()->month)
                    ->whereYear('sold_at', Carbon::now()->year)
                    ->count();
    }

    /**
     * Dispatch event to open the sale detail modal.
     */
    public function showSaleDetail(int $saleId): void
    {
        $this->dispatch('open-sale-detail-modal', sale: $saleId);
    }

    /**
     * Listen for created sale
     *
     * @return void
     */
    #[On('sale-created')]
    public function saleCreated(string $message)
    {
        $this->resetPage();
        session()->flash('message', $message);
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
