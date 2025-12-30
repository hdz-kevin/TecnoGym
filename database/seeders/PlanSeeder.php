<?php

namespace Database\Seeders;

use App\Enums\DurationUnit;
use App\Models\PlanType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var PlanType */
        $general = PlanType::create(['name' => 'General']);
        /** @var PlanType */
        $student = PlanType::create(['name' => 'Estudiante']);

        $general->plans()->createMany([
            [
                'name' => 'Mensual',
                'duration_value' => 1,
                'duration_unit' => DurationUnit::MONTH,
                'duration_in_days' => DurationUnit::MONTH->toDays() * 1,
                'price' => 400
            ],
            [
                'name' => '2 Semanas',
                'duration_value' => 2,
                'duration_unit' => DurationUnit::WEEK,
                'duration_in_days' => DurationUnit::WEEK->toDays() * 2,
                'price' => 250,
            ],
        ]);

        $student->plans()->createMany([
            [
                'name' => 'Mensual',
                'duration_value' => 1,
                'duration_unit' => DurationUnit::MONTH,
                'duration_in_days' => DurationUnit::MONTH->toDays() * 1,
                'price' => 350
            ],
            [
                'name' => '2 Semanas',
                'duration_value' => 2,
                'duration_unit' => DurationUnit::WEEK,
                'duration_in_days' => DurationUnit::WEEK->toDays() * 2,
                'price' => 200
            ],
        ]);
    }
}
