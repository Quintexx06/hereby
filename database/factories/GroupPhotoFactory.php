<?php

namespace Database\Factories;

use App\Models\GroupPhoto;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GroupPhoto>
 */
class GroupPhotoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'title' => 'Familie '.fake()->lastName(),
            'place' => null,
            'position' => 0,
        ];
    }
}
