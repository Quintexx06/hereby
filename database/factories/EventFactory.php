<?php

namespace Database\Factories;

use App\Enums\EventType;
use App\Models\Event;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'type' => EventType::Dinner,
            'name' => null,
            'starts_at' => fake()->dateTimeBetween('+3 months', '+12 months'),
            'ends_at' => null,
            'location_name' => fake()->company(),
            'address' => fake()->address(),
        ];
    }

    public function ofType(EventType $type): static
    {
        return $this->state(fn (): array => ['type' => $type]);
    }
}
