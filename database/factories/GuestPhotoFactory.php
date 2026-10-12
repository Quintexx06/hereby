<?php

namespace Database\Factories;

use App\Models\GuestPhoto;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GuestPhoto>
 */
class GuestPhotoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'path' => fn (array $attributes): string => GuestPhoto::directoryFor($attributes['wedding_id']).'/photos/'.fake()->uuid().'.jpg',
            'thumbnail_path' => null,
            'size' => fake()->numberBetween(500_000, 6_000_000),
        ];
    }
}
