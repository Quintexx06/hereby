<?php

namespace Database\Factories;

use App\Enums\ContentBlockType;
use App\Models\ContentBlock;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContentBlock>
 */
class ContentBlockFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'type' => ContentBlockType::Story,
            'event_id' => null,
            'position' => 0,
            'content' => ['de_CH' => ['title' => null, 'body' => fake()->paragraph()]],
        ];
    }
}
