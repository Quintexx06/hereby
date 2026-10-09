<?php

namespace App\Actions\Weddings;

use App\Enums\Locale;
use App\Enums\SetupStep;
use App\Enums\WeddingStatus;
use App\Enums\WeddingTheme;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Support\Str;

/**
 * Opens the setup: resumes the couple's draft if there is one, so a second
 * click never leaves an orphaned draft behind.
 */
class StartWeddingSetup
{
    public function handle(User $user): Wedding
    {
        $draft = $user->weddings()->where('status', WeddingStatus::Draft)->first();

        if ($draft) {
            return $draft;
        }

        $wedding = new Wedding([
            'slug' => Str::lower(Str::random(12)),
            'couple_names' => '',
            'default_locale' => Locale::GermanSwiss,
            'theme' => WeddingTheme::Ivory,
        ]);
        $wedding->owner()->associate($user);
        $wedding->forceFill([
            'status' => WeddingStatus::Draft,
            'setup_step' => SetupStep::Couple,
        ])->save();

        return $wedding;
    }
}
