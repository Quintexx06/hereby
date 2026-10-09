<?php

namespace App\Actions\Guests;

use App\Enums\Locale;
use App\Models\Wedding;
use Illuminate\Support\Facades\DB;

/**
 * Saves confirmed households and their guests in one go. New households are
 * invited to every event; the couple narrows that down per household later.
 */
class ImportHouseholds
{
    /**
     * @param  list<array{name: string, email?: string|null, locale?: string|null, plus_one_allowed?: bool, guests: list<array{first_name: string, last_name?: string|null, is_child?: bool}>}>  $households
     */
    public function handle(Wedding $wedding, array $households): int
    {
        $eventIds = $wedding->events()->pluck('id');

        DB::transaction(function () use ($wedding, $households, $eventIds): void {
            foreach ($households as $data) {
                $household = $wedding->households()->create([
                    'name' => $data['name'],
                    'email' => $data['email'] ?? null,
                    'locale' => isset($data['locale']) ? Locale::from($data['locale']) : $wedding->default_locale,
                    'plus_one_allowed' => (bool) ($data['plus_one_allowed'] ?? false),
                ]);

                $household->guests()->createMany(array_map(fn (array $guest): array => [
                    'first_name' => $guest['first_name'],
                    'last_name' => $guest['last_name'] ?? null,
                    'is_child' => (bool) ($guest['is_child'] ?? false),
                ], $data['guests']));

                $household->events()->attach($eventIds);
            }
        });

        return count($households);
    }
}
