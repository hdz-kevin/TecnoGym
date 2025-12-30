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
        $members = Member::inRandomOrder()->limit(80)->get();

        // Plans
        $allPlans = [
            'general' => [
                $genMonthly,
                $genBiweekly,
            ],
            'student' => [
                $stuMonthly,
                $stuBiweekly,
            ]
        ];

        // Create memberships
        foreach ($members as $member) {
            $planType = (rand(1, 10) <= 7) ? 'general' : 'student';
            $plans = $allPlans[$planType];
            $plan = $plans[array_rand($plans)];

            Membership::create([
                'member_id' => $member->id,
                'plan_id' => $plan->id,
                'plan_type_id' => $plan->plan_type_id,
            ]);
        }
    }
}
