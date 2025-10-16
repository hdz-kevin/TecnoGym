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
      $gender = fake()->randomElement(MemberGender::cases());

        return [
            'name' => fake()->name($gender->value == 'M' ? 'male' : 'female'),
            'gender' => $gender,
            'birth_date' => fake()->date(),
            'photo' => null,
        ];
    }
}
