<?php

namespace Database\Seeders;

use App\Enums\DurationUnit;
use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\Membership;
use App\Models\PlanType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Plan Types
        $general = PlanType::where('name', 'General')->first();
        $student = PlanType::where('name', 'Estudiante')->first();

        // General Plans
        $genMonthly = $general->plans()
                            ->where('duration_value', 1)
                            ->where('duration_unit', DurationUnit::MONTH)
                            ->first();
        $genBiweekly = $general->plans()
                            ->where('duration_value', 2)
                            ->where('duration_unit', DurationUnit::WEEK)
                            ->first();
        // Student Plans
        $stuMonthly = $student->plans()
                            ->where('duration_value', 1)
                            ->where('duration_unit', DurationUnit::MONTH)
                            ->first();
        $stuBiweekly = $student->plans()
                            ->where('duration_value', 2)
                            ->where('duration_unit', DurationUnit::WEEK)
                            ->first();

        // Members
        $members = Member::all();

        // Memberships
        $memberships = [
            // General
            ['member' => $members[0], 'plan' => $genMonthly],
            ['member' => $members[1], 'plan' => $genMonthly],
            ['member' => $members[5], 'plan' => $genBiweekly],
            ['member' => $members[7], 'plan' => $genMonthly],
            // Student
            ['member' => $members[4], 'plan' => $stuMonthly],
            ['member' => $members[6], 'plan' => $stuBiweekly],
            ['member' => $members[8], 'plan' => $stuMonthly],
        ];

        // Create memberships
        foreach ($memberships as $membership) {
            $plan = $membership['plan'];

            Membership::create([
                'member_id' => $membership['member']->id,
                'plan_id' => $plan->id,
                'plan_type_id' => $plan->plan_type_id,
            ]);
        }
    }
}
