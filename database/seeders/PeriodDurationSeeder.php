<?php

namespace Database\Seeders;

use App\Enums\DurationUnit;
use App\Models\MembershipType;
use Illuminate\Database\Seeder;

class PeriodDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $general = MembershipType::where('name', 'General')->first();
        $student = MembershipType::where('name', 'Estudiante')->first();

        $general->periodDurations()
                ->createMany([
                    [
                        'name' => '2 Semanas',
                        'duration_value' => 2,
                        'duration_unit' => DurationUnit::WEEK,
                        'price' => 250,
                    ],
                    [
                        'name' => 'Mensual',
                        'duration_value' => 1,
                        'duration_unit' => DurationUnit::MONTH,
                        'price' => 400,
                    ],
                ]);

        $student->periodDurations()
                ->createMany([
                    [
                        'name' => '2 Semanas',
                        'duration_value' => 2,
                        'duration_unit' => DurationUnit::WEEK,
                        'price' => 200,
                    ],
                    [
                        'name' => 'Mensual',
                        'duration_value' => 1,
                        'duration_unit' => DurationUnit::MONTH,
                        'price' => 350,
                    ],
                ]);
    }
}
