<?php

namespace App\Actions\Account;

use App\Models\Guest;
use App\Models\User;

/**
 * What the account pages show beside the forms: whose wedding this is, where
 * its data lives and the day it is deleted (ADR 0007), and how sign-in is
 * protected. Counts only: no guest data leaves the server here.
 */
class BuildAccountSummary
{
    /**
     * @return array{since: string, wedding: array{couple_names: string, date: string|null, deletes_on: string|null, guests: int}|null, two_factor: bool, passkeys: int}
     */
    public function handle(User $user): array
    {
        $wedding = $user->weddings()->first();

        return [
            'since' => $user->created_at?->toDateString() ?? now()->toDateString(),
            'wedding' => $wedding ? [
                'couple_names' => $wedding->couple_names,
                'date' => $wedding->wedding_date?->toDateString(),
                'deletes_on' => $wedding->wedding_date?->copy()
                    ->addMonths(config()->integer('hereby.retention_months'))->toDateString(),
                'guests' => Guest::query()->whereHas('household', fn ($query) => $query->where('wedding_id', $wedding->id))->count(),
            ] : null,
            'two_factor' => $user->two_factor_confirmed_at !== null,
            'passkeys' => $user->passkeys()->count(),
        ];
    }
}
