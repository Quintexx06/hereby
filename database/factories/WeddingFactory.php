<?php

namespace Database\Factories;

use App\Enums\Locale;
use App\Enums\WeddingTheme;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Wedding>
 */
class WeddingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $coupleNames = fake()->firstName().' & '.fake()->firstName();
        $weddingDate = fake()->dateTimeBetween('+3 months', '+12 months');

        return [
            'owner_id' => User::factory(),
            'slug' => Str::slug($coupleNames).'-'.Str::lower(Str::random(4)),
            'couple_names' => $coupleNames,
            'wedding_date' => $weddingDate,
            'rsvp_deadline' => (clone $weddingDate)->modify('-6 weeks'),
            'default_locale' => Locale::GermanSwiss,
            'theme' => WeddingTheme::Ivory,
        ];
    }

    /**
     * A wedding that took place the given number of months ago.
     */
    public function heldMonthsAgo(int $months): static
    {
        return $this->state(fn (): array => [
            'wedding_date' => now()->subMonths($months)->toDateString(),
            'rsvp_deadline' => null,
        ]);
    }
}
