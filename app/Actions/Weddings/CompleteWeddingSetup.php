<?php

namespace App\Actions\Weddings;

use App\Enums\SetupStep;
use App\Enums\WeddingStatus;
use App\Models\Wedding;
use Illuminate\Support\Str;

/**
 * Turns a finished draft into an active wedding. Returns the first step that
 * still lacks an answer instead, so nothing half-filled goes live.
 */
class CompleteWeddingSetup
{
    public function handle(Wedding $wedding): ?SetupStep
    {
        $missing = $this->firstIncompleteStep($wedding);

        if ($missing) {
            return $missing;
        }

        $wedding->forceFill([
            'status' => WeddingStatus::Active,
            'setup_completed_at' => now(),
            'slug' => Str::slug(str_replace('&', 'und', $wedding->couple_names)).'-'.Str::lower(Str::random(4)),
        ])->save();

        return null;
    }

    public function firstIncompleteStep(Wedding $wedding): ?SetupStep
    {
        return match (true) {
            ! $wedding->partner_one || ! $wedding->partner_two => SetupStep::Couple,
            ! $wedding->wedding_date => SetupStep::Date,
            ! $wedding->celebration || ! $wedding->events()->exists() => SetupStep::Programme,
            ! $wedding->guest_estimate || ! $wedding->languages?->isNotEmpty() => SetupStep::Guests,
            default => null,
        };
    }
}
