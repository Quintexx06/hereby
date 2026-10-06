<?php

namespace Database\Factories;

use App\Models\Guest;
use App\Models\Household;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guest>
 */
class GuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'household_id' => Household::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'is_child' => false,
            'is_plus_one' => false,
            'dietary_notes' => null,
        ];
    }

    public function child(): static
    {
        return $this->state(fn (): array => ['is_child' => true]);
    }
}
