<?php

namespace App\Livewire;

use App\Models\Period;
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
     * Build a report array for a given date range.
     * Returns visits earnings, new memberships and renewals count and income.
     *
     * @param  \Carbon\Carbon  $from
     * @param  \Carbon\Carbon  $to
     * @return array
     */
    private function reportFor($from, $to): array
    {
        // Visits in range
        $visits = Visit::whereBetween('visit_at', [$from, $to]);

        // New memberships: periods in range whose membership had NO prior periods before the range
        $newMembershipsQuery = Period::whereBetween('created_at', [$from, $to])
            ->whereHas('membership', function ($q) use ($from) {
                $q->whereDoesntHave('periods', function ($q2) use ($from) {
                    $q2->where('created_at', '<', $from);
                });
            });

        // Renewals: periods in range whose membership already had at least one period before the range
        $renewalsQuery = Period::whereBetween('created_at', [$from, $to])
            ->whereHas('membership', function ($q) use ($from) {
                $q->whereHas('periods', function ($q2) use ($from) {
                    $q2->where('created_at', '<', $from);
                });
            });

        return [
            'visits_count'    => $visits->count(),
            'visits_income'   => $visits->sum('price_paid'),
            'new_count'       => $newMembershipsQuery->count(),
            'new_income'      => $newMembershipsQuery->sum('price_paid'),
            'renewals_count'  => $renewalsQuery->count(),
            'renewals_income' => $renewalsQuery->sum('price_paid'),
        ];
    }

    /**
     * Report for today
     */
    #[Computed]
    public function today(): array
    {
        return $this->reportFor(now()->startOfDay(), now()->endOfDay());
    }

    /**
     * Report for the current week (Mon–Sun)
     */
    #[Computed]
    public function thisWeek(): array
    {
        return $this->reportFor(now()->startOfWeek(), now()->endOfWeek());
    }

    /**
     * Report for the current month
     */
    #[Computed]
    public function thisMonth(): array
    {
        return $this->reportFor(now()->startOfMonth(), now()->endOfMonth());
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.dashboard');
    }
}
