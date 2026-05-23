<?php

namespace App\Livewire;

use App\Models\Period;
use App\Models\Sale;
use App\Models\Visit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    /**
     * Period tabs
     */
    public array $periods = [
        'today' => 'Hoy',
        'week'  => 'Esta semana',
        'month' => 'Este mes',
    ];
    
    /**
     * Selected period tab
     */
    public string $activePeriod = 'today';

    /**
     * Set the active period tab
     *
     * @param string $period Period key
     * @return void
     */
    public function setPeriod(string $period): void
    {
        $this->activePeriod = $period;
    }

    /**
     * Get the date range for the active period
     *
     * @return array{0: \Carbon\Carbon, 1: \Carbon\Carbon}
     */
    private function dateRange(): array
    {
        return match ($this->activePeriod) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week'  => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    /**
     * Visits in the active period
     */
    #[Computed]
    public function visits()
    {
        [$from, $to] = $this->dateRange();

        return Visit::whereBetween('visit_at', [$from, $to])
                     ->orderBy('visit_at', 'desc')
                     ->get();
    }

    /**
     * Sales in the active period
     */
    #[Computed]
    public function sales()
    {
        [$from, $to] = $this->dateRange();

        return Sale::whereBetween('sold_at', [$from, $to])
                     ->with('productSales')
                     ->orderBy('sold_at', 'desc')
                     ->get();
    }

    /**
     * New memberships in the active period.
     * New memberships: periods in range whose membership had NO prior periods before the range.
     */
    #[Computed]
    public function newMemberships()
    {
        [$from, $to] = $this->dateRange();

        return Period::with(['membership.member', 'membership.membershipType', 'duration'])
            ->whereBetween('created_at', [$from, $to])
            ->whereHas('membership', function ($q) use ($from) {
                $q->whereDoesntHave('periods', function ($q2) use ($from) {
                    $q2->where('created_at', '<', $from);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Renewals in the active period.
     * Renewals: periods in range whose membership already had at least one period before the range.
     */
    #[Computed]
    public function renewals()
    {
        [$from, $to] = $this->dateRange();

        return Period::with(['membership.member', 'membership.membershipType', 'duration'])
            ->whereBetween('created_at', [$from, $to])
            ->whereHas('membership', function ($q) use ($from) {
                $q->whereHas('periods', function ($q2) use ($from) {
                    $q2->where('created_at', '<', $from);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get total earnings
     *
     * @return float
     */
    #[Computed]
    public function totalEarnings(): float
    {
        return $this->newMemberships->sum('price_paid')
             + $this->renewals->sum('price_paid')
             + $this->visits->sum('price')
             + $this->sales->sum('total');
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.dashboard');
    }
}
