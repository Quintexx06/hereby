<?php

namespace App\Actions\Guests;

use App\Models\Household;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Saves a household as edited: details, invited events, and its people.
 * People missing from the list are removed, with their answers.
 */
class UpdateHousehold
{
    /**
     * @param  array{name: string, email: string|null, locale: string, plus_one_allowed: bool, events: list<int>, guests: list<array{id: int|null, first_name: string, last_name: string|null, is_child: bool}>}  $data
     */
    public function handle(Household $household, array $data): void
    {
        DB::transaction(function () use ($household, $data): void {
            $household->update(Arr::only($data, ['name', 'email', 'locale', 'plus_one_allowed']));
            $household->events()->sync($data['events']);

            $kept = array_values(array_filter(array_column($data['guests'], 'id')));
            $household->guests()->whereNotIn('id', $kept)->delete();

            foreach ($data['guests'] as $guest) {
                $attributes = [
                    'first_name' => $guest['first_name'],
                    'last_name' => $guest['last_name'],
                    'is_child' => $guest['is_child'],
                ];

                if (isset($guest['id'])) {
                    $household->guests()->whereKey($guest['id'])->firstOrFail()->update($attributes);
                } else {
                    $household->guests()->create($attributes);
                }
            }
        });
    }
}
