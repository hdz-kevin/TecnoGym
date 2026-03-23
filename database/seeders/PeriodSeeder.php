<?php

namespace Database\Seeders;

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
        $memberships = Membership::with('membershipType.durations')->get();

        foreach ($memberships as $membership) {
            $membershipType = $membership->membershipType;
            $periodsToCreate = rand(1, 9);

            // Start a few months ago
            $startDate = Carbon::now()->subMonths($periodsToCreate - 1);

            for ($i = 0; $i < $periodsToCreate; $i++) {
                $duration = $membershipType->durations->random();
                // Calculate dates
                $periodStartDate = $startDate;
                $periodEndDate = Period::endDateFrom($periodStartDate, $duration);
                // Set Status
                $status = PeriodStatus::fromDates($periodStartDate, $periodEndDate);

                // Create Period
                $period = Period::create([
                    'membership_id' => $membership->id,
                    'duration_id' => $duration->id,
                    'start_date' => $periodStartDate,
                    'end_date' => $periodEndDate,
                    'price_paid' => $duration->price,
                    'status' => $status,
                ]);

                // Sync created_at to the period's start_date
                Period::withoutTimestamps(fn () => $period->update(['created_at' => $periodStartDate->startOfDay()]));

                // Start date of the next period
                $startDate = $periodEndDate;

                // Simulate some random breaks between periods
                if (rand(1, 10) <= 3) {
                    $startDate->addDays(rand(1, 20));
                }
            }
        }

        // Update Membership status
        $memberships->load(['periods', 'member']);

        $memberships->each(function ($membership) {
            if ($membership->recent_period->status === PeriodStatus::IN_PROGRESS) {
                $membership->status = MembershipStatus::ACTIVE;
                $membership->save();
                $membership->member->status = MemberStatus::ACTIVE;
                $membership->member->save();
            } else {
                $membership->status = MembershipStatus::EXPIRED;
                $membership->save();
                $membership->member->status = MemberStatus::EXPIRED;
                $membership->member->save();
            }

            // Set created_at to match first period start date
            $firstPeriod = $membership->periods->last();
            $membership->created_at = $firstPeriod->start_date;
            $membership->save();
        });
    }
}
