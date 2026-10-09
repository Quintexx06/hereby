<?php

namespace Database\Factories;

use App\Enums\Locale;
use App\Enums\SetupStep;
use App\Enums\WeddingStatus;
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
        [$partnerOne, $partnerTwo] = [fake()->firstName(), fake()->firstName()];
        $coupleNames = $partnerOne.' & '.$partnerTwo;
        $weddingDate = fake()->dateTimeBetween('+3 months', '+12 months');

        return [
            'owner_id' => User::factory(),
            'slug' => Str::slug($coupleNames).'-'.Str::lower(Str::random(4)),
            'status' => WeddingStatus::Active,
            'setup_step' => SetupStep::Review,
            'setup_completed_at' => now(),
            'couple_names' => $coupleNames,
            'partner_one' => $partnerOne,
            'partner_two' => $partnerTwo,
            'languages' => [Locale::GermanSwiss],
            'wedding_date' => $weddingDate,
            'rsvp_deadline' => (clone $weddingDate)->modify('-6 weeks'),
            'default_locale' => Locale::GermanSwiss,
            'theme' => WeddingTheme::Ivory,
        ];
    }

    /**
     * A wedding still in setup, stopped at the given step.
     */
    public function draft(SetupStep $step = SetupStep::Couple): static
    {
        return $this->state(fn (): array => [
            'status' => WeddingStatus::Draft,
            'setup_step' => $step,
            'setup_completed_at' => null,
        ]);
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
