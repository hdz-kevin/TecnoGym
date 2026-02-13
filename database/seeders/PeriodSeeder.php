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
        $memberships = Membership::with('membershipType.periodTypes')->get();

        foreach ($memberships as $membership) {
            $membershipType = $membership->membershipType;
            $periodsToCreate = rand(1, 9);

            // Start a few months ago
            $startDate = Carbon::now()->subMonths(ceil($periodsToCreate / 2));

            for ($i = 0; $i < $periodsToCreate; $i++) {
                $periodType = $membershipType->periodTypes->random();
                // Calculate dates
                $periodStartDate = $startDate->copy();
                $periodEndDate = match ($periodType->duration_unit) {
                    DurationUnit::DAY => $periodStartDate->copy()->addDays($periodType->duration_value),
                    DurationUnit::WEEK => $periodStartDate->copy()->addWeeks($periodType->duration_value),
                    DurationUnit::MONTH => $periodStartDate->copy()->addMonths($periodType->duration_value),
                    default => $periodStartDate->copy()->addMonth(),
                };

                // Get Status
                $status = PeriodStatus::COMPLETED;

                if ($periodStartDate <= Carbon::now() && Carbon::now() <= $periodEndDate) {
                    $status = PeriodStatus::IN_PROGRESS;
                } else if ($periodStartDate > Carbon::now()) {
                    // In case a FUTURE status is added to Period model
                    $status = PeriodStatus::IN_PROGRESS;
                }

                // Create Period
                Period::create([
                    'membership_id' => $membership->id,
                    'period_type_id' => $periodType->id,
                    'start_date' => $periodStartDate->toDateString(),
                    'end_date' => $periodEndDate->toDateString(),
                    'price_paid' => $periodType->price,
                    'status' => $status,
                ]);

                // Start date of the next period
                $startDate = $periodEndDate;

                // Simulate some random breaks between periods
                if (rand(1, 10) <= 3) {
                    $startDate->addDays(rand(2, 30));
                }
            }
        }

        // Update Membership status
        $memberships->load(['periods', 'member']);

        $memberships->each(function ($membership) {
            if ($membership->currentPeriod()->first()) {
                $membership->status = MembershipStatus::ACTIVE;
                $membership->member->status = MemberStatus::ACTIVE;
                $membership->member->save();
                $membership->save();
            } else {
                // Membership status is EXPIRED by default
                $membership->member->status = MemberStatus::EXPIRED;
                $membership->member->save();
            }

            // Set created_at to match first period start date
            $firstPeriod = $membership->periods->sortBy('start_date')->first();
            $membership->created_at = $firstPeriod->start_date;
            $membership->save();
        });
    }
}
