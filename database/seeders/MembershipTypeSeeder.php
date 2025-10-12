<?php

namespace Database\Seeders;

use App\Enums\DurationUnit;
use App\Models\MembershipType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var MembershipType */
        $general = MembershipType::create(['name' => 'General']);
        /** @var MembershipType */
        $student = MembershipType::create(['name' => 'Estudiante']);

        $general->periods()->createMany([
            ['name' => 'Mensual', 'duration_value' => 1, 'duration_unit' => DurationUnit::MONTH, 'price' => 400],
            ['name' => '15 días', 'duration_value' => 2, 'duration_unit' => DurationUnit::WEEK, 'price' => 250],
        ]);

        $student->periods()->createMany([
            ['name' => 'Mensual', 'duration_value' => 1, 'duration_unit' => DurationUnit::MONTH, 'price' => 350],
            ['name' => '15 días', 'duration_value' => 2, 'duration_unit' => DurationUnit::WEEK, 'price' => 200],
        ]);
    }
}
