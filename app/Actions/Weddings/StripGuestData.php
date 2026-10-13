<?php

namespace App\Actions\Weddings;

use App\Models\Wedding;
use Illuminate\Support\Facades\DB;

/**
 * Guest data leaves on schedule even when the couple keeps their memories
 * (ADR 0011): households, guests, answers, offers and group photos go once
 * the retention window ends; the photos and the newspaper stay with the
 * wedding until the kept year is over, then model:prune takes the rest.
 */
class StripGuestData
{
    /**
     * @return int Weddings stripped.
     */
    public function handle(): int
    {
        $cutoff = now()->subMonths(config()->integer('hereby.retention_months'))->startOfDay();

        $weddings = Wedding::query()
            ->where('wedding_date', '<', $cutoff)
            ->whereDate('memories_kept_until', '>=', now()->toDateString())
            ->whereNull('guest_data_deleted_at')
            ->get();

        $weddings->each(fn (Wedding $wedding) => DB::transaction(function () use ($wedding): void {
            // Guests, answers and memberships cascade at the database level.
            $wedding->households()->delete();
            $wedding->programmeItems()->delete();
            $wedding->groupPhotos()->delete();
            $wedding->forceFill(['guest_data_deleted_at' => now(), 'way_home_sent' => null])->save();
        }));

        return $weddings->count();
    }
}
