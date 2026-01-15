<?php

namespace Database\Seeders;

use App\Models\Visit;
use App\Models\VisitType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visitType = VisitType::create([
            'name' => 'General',
            'price' => 40,
        ]);

        Visit::factory(15)->create([
            'visit_type_id' => $visitType->id,
            'price_paid' => $visitType->price,
        ]);
    }
}
