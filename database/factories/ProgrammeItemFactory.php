<?php

namespace Database\Factories;

use App\Enums\ProgrammeKind;
use App\Enums\ProgrammeStatus;
use App\Models\ProgrammeItem;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProgrammeItem>
 */
class ProgrammeItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'household_id' => null,
            'contact_name' => fake()->firstName(),
            'kind' => fake()->randomElement(ProgrammeKind::cases()),
            'title' => fake()->sentence(3),
            'notes' => null,
            'minutes' => fake()->numberBetween(3, 15),
            'status' => ProgrammeStatus::Pending,
            'position' => 0,
        ];
    }
}
