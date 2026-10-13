<?php

namespace Database\Factories;

use App\Models\Wedding;
use App\Models\WeddingPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WeddingPhoto>
 */
class WeddingPhotoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'path' => fn (array $attributes): string => WeddingPhoto::directoryFor($attributes['wedding_id']).'/'.fake()->uuid().'.jpg',
            'position' => 0,
            'width' => 1600,
            'height' => 1067,
        ];
    }
}
