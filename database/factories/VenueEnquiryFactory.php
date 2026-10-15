<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\VenueEnquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VenueEnquiry>
 */
class VenueEnquiryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'partner_id' => Partner::factory()->showcase(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'preferred_date' => now()->addMonths(3)->toDateString(),
            'message' => 'Wir heiraten nächsten Sommer und würden gern vorbeikommen.',
        ];
    }
}
