<?php

namespace Database\Factories;

use App\Enums\Locale;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Household>
 */
class HouseholdFactory extends Factory
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
            'name' => 'Familie '.fake()->lastName(),
            'locale' => Locale::GermanSwiss,
            'plus_one_allowed' => false,
        ];
    }

    public function speaking(Locale $locale): static
    {
        return $this->state(fn (): array => ['locale' => $locale]);
    }
}
