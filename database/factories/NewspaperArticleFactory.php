<?php

namespace Database\Factories;

use App\Models\NewspaperArticle;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NewspaperArticle>
 */
class NewspaperArticleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'author_name' => fake()->firstName(),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraphs(2, true),
            'photo_path' => null,
            'position' => 0,
        ];
    }
}
