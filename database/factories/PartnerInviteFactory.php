<?php

namespace Database\Factories;

use App\Enums\PartnerType;
use App\Models\PartnerInvite;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PartnerInvite>
 */
class PartnerInviteFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'type' => PartnerType::Photographer,
            'name' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
