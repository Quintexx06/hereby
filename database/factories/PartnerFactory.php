<?php

namespace Database\Factories;

use App\Enums\PartnerType;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => PartnerType::Venue,
            'name' => 'Seehotel '.fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->streetAddress(),
            'postcode' => (string) fake()->numberBetween(1000, 9999),
            'town' => fake()->city(),
            'capacity' => 120,
            'travel' => 'Mit dem Zug bis Luzern, dann Bus 21.',
            'stay' => '42 Zimmer mit Seesicht.',
        ];
    }

    public function photographer(): static
    {
        return $this->state(fn (): array => ['type' => PartnerType::Photographer, 'name' => fake()->name().' Fotografie']);
    }

    public function planner(): static
    {
        return $this->state(fn (): array => ['type' => PartnerType::Planner, 'name' => fake()->name().' Events']);
    }

    public function showcase(): static
    {
        return $this->state(fn (): array => ['is_showcase' => true]);
    }
}
