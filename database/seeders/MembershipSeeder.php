<?php

namespace Database\Seeders;

use App\Enums\DurationUnit;
use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = Member::all()->take(7);

        // Membership Types
        $general = MembershipType::where('name', 'general')->first();
        $student = MembershipType::where('name', 'estudiante')->first();

        // General Periods
        $genMonthly = $general->periods()
                            ->where('duration_value', 1)
                            ->where('duration_unit', DurationUnit::MONTH)
                            ->first();
        $genBiweekly = $general->periods()
                            ->where('duration_value', 2)
                            ->where('duration_unit', DurationUnit::WEEK)
                            ->first();
        // Student Periods
        $stuMonthly = $student->periods()
                            ->where('duration_value', 1)
                            ->where('duration_unit', DurationUnit::MONTH)
                            ->first();
        $stuBiweekly = $student->periods()
                            ->where('duration_value', 2)
                            ->where('duration_unit', DurationUnit::WEEK)
                            ->first();

        $memberships = [
            // General
            [
                'member_id' => $members[0]->id,
                'period' => $genMonthly,
                'start_date' => Carbon::now()->subDays(10),
                'status' => MembershipStatus::ACTIVE,
            ],
            [
                'member_id' => $members[1]->id,
                'period' => $genMonthly,
                'start_date' => Carbon::now()->subDays(15),
                'status' => MembershipStatus::ACTIVE,
            ],
            [
                'member_id' => $members[2]->id,
                'period' => $genBiweekly,
                'start_date' => Carbon::now()->subDays(1),
                'status' => MembershipStatus::ACTIVE,
            ],
            [
                'member_id' => $members[3]->id,
                'period' => $genMonthly,
                'start_date' => Carbon::now()->subDays(45),
                'status' => MembershipStatus::EXPIRED,
            ],
            // Student
            [
                'member_id' => $members[4]->id,
                'period' => $stuMonthly,
                'start_date' => Carbon::now()->subDays(7),
                'status' => MembershipStatus::ACTIVE,
            ],
            [
                'member_id' => $members[5]->id,
                'period' => $stuBiweekly,
                'start_date' => Carbon::now()->subDays(12),
                'status' => MembershipStatus::ACTIVE,
            ],
            [
                'member_id' => $members[6]->id,
                'period' => $stuBiweekly,
                'start_date' => Carbon::now()->subDays(15),
                'status' => MembershipStatus::EXPIRED,
            ],
        ];

        foreach ($memberships as $membership) {
            $period = $membership['period'];
            $startDate = $membership['start_date'];

            Membership::create([
                'member_id' => $membership['member_id'],
                'membership_type_id' => $period->membership_type_id,
                'period_id' => $period->id,
                'price' => $period->price,
                'start_date' => $startDate,
                'end_date' => $period->duration_value == 1 && $period->duration_unit == DurationUnit::MONTH
                                ? $startDate->copy()->addMonth()
                                : $startDate->copy()->addWeeks(2),
                'status' => $membership['status'],
            ]);
        }
    }
}
