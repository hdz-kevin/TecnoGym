<?php

namespace Database\Seeders;

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membership Types
        $general = MembershipType::where('name', 'General')->first();
        $student = MembershipType::where('name', 'Estudiante')->first();

        // Members
        $members = Member::inRandomOrder()->limit(70)->get();

        // Create memberships
        foreach ($members as $member) {
            $membershipType = (rand(1, 7) <= 4) ? $general : $student;

            Membership::create([
                'member_id' => $member->id,
                'membership_type_id' => $membershipType->id,
                'status' => MembershipStatus::EXPIRED->value,
            ]);
        }
    }
}
