<?php

namespace Database\Seeders;

use App\Enums\DurationUnit;
use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use App\Enums\PeriodStatus;
use App\Models\Membership;
use App\Models\Period;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $memberships = Membership::with(['plan', 'member'])->get();

        foreach ($memberships as $membership) {
            $plan = $membership->plan;

            $periodsToCreate = rand(1, 6);

            // Start a few months ago. Few periods are created on the current date.
            $startDate = Carbon::now()->subMonths(ceil($periodsToCreate / 1.6) + 1);

            for ($i = 0; $i < $periodsToCreate; $i++) {
                $periodStart = $startDate->copy();

                $periodEnd = match ($plan->duration_unit) {
                    DurationUnit::DAY => $periodStart->copy()->addDays($plan->duration_value),
                    DurationUnit::WEEK => $periodStart->copy()->addWeeks($plan->duration_value),
                    DurationUnit::MONTH => $periodStart->copy()->addMonths($plan->duration_value),
                    default => $periodStart->copy()->addMonth(),
                };

                // Period status
                $isCurrentPeriod = $periodStart <= Carbon::now() && Carbon::now() <= $periodEnd;
                $isFuturePeriod = $periodStart > Carbon::now();
                $status = match (true) {
                    $isCurrentPeriod => PeriodStatus::IN_PROGRESS,
                    $isFuturePeriod => PeriodStatus::IN_PROGRESS,
                    default => PeriodStatus::COMPLETED
                };

                // Create the period
                Period::create([
                    'membership_id' => $membership->id,
                    'start_date' => $periodStart->toDateString(),
                    'end_date' => $periodEnd->toDateString(),
                    'price_paid' => $plan->price,
                    'status' => $status,
                ]);

                // Start date of the next period
                $startDate = $periodEnd;

                // Simulate some random breaks between periods
                if (rand(1, 10) <= 3) { // 30% chance of break
                    $startDate->addDays(rand(2, 30));
                }
            }
        }

        $memberships->load('periods');

        $memberships->each(function ($membership) {
            if ($membership->currentPeriod()->first()) {
                $membership->status = MembershipStatus::ACTIVE;
                $membership->member->status = MemberStatus::ACTIVE;
                $membership->member->save();
                $membership->save();
            } else {
                $membership->status = MembershipStatus::EXPIRED;
                $membership->member->status = MemberStatus::EXPIRED;
                $membership->member->save();
                $membership->save();
            }

            // Set created_at to match first period start date
            $firstPeriod = $membership->periods->sortBy('start_date')->first();
            $membership->created_at = $firstPeriod->start_date;
            $membership->save();
        });
    }
}
