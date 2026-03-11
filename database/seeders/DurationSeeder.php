<?php

namespace Database\Seeders;

use App\Enums\DurationUnit;
use App\Models\MembershipType;
use Illuminate\Database\Seeder;

class DurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var MembershipType $general */
        $general = MembershipType::where('name', 'General')->first();
        /** @var MembershipType $student */
        $student = MembershipType::where('name', 'Estudiante')->first();

        $general->durations()
                ->createMany([
                    [
                        'name' => '2 Semanas',
                        'amount' => 2,
                        'unit' => DurationUnit::WEEK,
                        'price' => 250,
                    ],
                    [
                        'name' => 'Mensual',
                        'amount' => 1,
                        'unit' => DurationUnit::MONTH,
                        'price' => 400,
                    ],
                ]);

        $student->durations()
                ->createMany([
                    [
                        'name' => '2 Semanas',
                        'amount' => 2,
                        'unit' => DurationUnit::WEEK,
                        'price' => 200,
                    ],
                    [
                        'name' => 'Mensual',
                        'amount' => 1,
                        'unit' => DurationUnit::MONTH,
                        'price' => 350,
                    ],
                ]);
    }
}
