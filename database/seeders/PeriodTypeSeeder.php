<?php

namespace Database\Seeders;

use App\Enums\DurationUnit;
use App\Models\MembershipType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $general = MembershipType::where('name', 'General')->first();
        $student = MembershipType::where('name', 'Estudiante')->first();

        $general->periodTypes()
            ->createMany([
                [
                    'name' => '2 Semanas',
                    'duration_value' => 2,
                    'duration_unit' => DurationUnit::WEEK,
                    'price' => 250,
                    'duration_in_days' => DurationUnit::WEEK->toDays() * 2,
                ],
                [
                    'name' => 'Mensual',
                    'duration_value' => 1,
                    'duration_unit' => DurationUnit::MONTH,
                    'price' => 400,
                    'duration_in_days' => DurationUnit::MONTH->toDays(),
                ],
            ]);

        $student->periodTypes()
            ->createMany([
                [
                    'name' => '2 Semanas',
                    'duration_value' => 2,
                    'duration_unit' => DurationUnit::WEEK,
                    'price' => 200,
                    'duration_in_days' => DurationUnit::WEEK->toDays() * 2,
                ],
                [
                    'name' => 'Mensual',
                    'duration_value' => 1,
                    'duration_unit' => DurationUnit::MONTH,
                    'price' => 350,
                    'duration_in_days' => DurationUnit::MONTH->toDays(),
                ],
            ]);
    }
}
