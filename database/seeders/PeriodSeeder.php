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
                $periodStartDate = $startDate->copy();
                $periodEndDate = Period::calculateEndDate($periodStartDate, $duration);

                // Set Status
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
                    'duration_id' => $duration->id,
                    'start_date' => $periodStartDate->toDateString(),
                    'end_date' => $periodEndDate->toDateString(),
                    'price_paid' => $duration->price,
                    'status' => $status,
                ]);

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
