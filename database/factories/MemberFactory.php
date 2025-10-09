<?php

namespace Database\Factories;

use App\Enums\MemberGender;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'gender' => fake()->randomElement(MemberGender::values()),
            'birth_date' => fake()->date(),
            'photo' => null,
        ];
    }
}
