<?php

namespace Database\Factories;

use App\Enums\ResponseStatus;
use App\Models\Event;
use App\Models\EventResponse;
use App\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventResponse>
 */
class EventResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guest_id' => Guest::factory(),
            'event_id' => Event::factory(),
            'status' => ResponseStatus::Pending,
            'menu_choice' => null,
            'responded_at' => null,
        ];
    }

    public function attending(): static
    {
        return $this->state(fn (): array => [
            'status' => ResponseStatus::Attending,
            'responded_at' => now(),
        ]);
    }
}
