<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = now()->subMonth();

        return [
            'visit_at' => $startDate->addDays(rand(0, 30))->addHours(rand(0, 24)),
            'price_paid' => 40,
        ];
    }
}
