<?php

namespace App\Actions\Evening;

use App\Models\GroupPhoto;
use App\Models\Guest;
use App\Models\Household;
use App\Support\WayHome;
use Illuminate\Support\Collection;

/**
 * What one household sees of «Der Abend» on its personal link (spec
 * 2026-10-13). Null without Atelier. Other guests appear only by first
 * name and the couple's note, and only at the household's own tables.
 */
class BuildEvening
{
    /**
     * @return array{tables: list<array{name: string, yours: list<string>, mates: list<array{name: string, note: string|null}>}>, wayHome: list<array{label: string, place: string|null, departsAt: string}>, call: array{title: string, place: string|null}|null, offerUrl: string|null}|null
     */
    public function handle(Household $household): ?array
    {
        $wedding = $household->wedding;

        if (! $wedding->includesEvening()) {
            return null;
        }

        return [
            'tables' => $wedding->seating_published_at !== null && $household->exists ? $this->tables($household) : [],
            'wayHome' => array_map(fn (array $connection): array => [
                'label' => $connection['label'],
                'place' => $connection['place'],
                'departsAt' => $connection['departs']->toIso8601String(),
            ], WayHome::connections($wedding)),
            'call' => $household->exists ? $this->call($household) : null,
            // Offers come after the reply, never before it (rule 1).
            'offerUrl' => $household->exists && $household->responded_at !== null
                ? route('invitation.programme', $household)
                : null,
        ];
    }

    /**
     * A group photo the witnesses just called this household to.
     *
     * @return array{title: string, place: string|null}|null
     */
    private function call(Household $household): ?array
    {
        $group = $household->groupPhotos()
            ->where('called_at', '>=', now()->subMinutes(GroupPhoto::CALL_MINUTES))
            ->latest('called_at')
            ->first();

        return $group ? ['title' => $group->title, 'place' => $group->place] : null;
    }

    /**
     * @return list<array{name: string, yours: list<string>, mates: list<array{name: string, note: string|null}>}>
     */
    private function tables(Household $household): array
    {
        /** @var Collection<string, Collection<int, Guest>> $own */
        $own = $household->guests->filter(fn (Guest $guest): bool => filled($guest->table_name))->groupBy('table_name');

        if ($own->isEmpty()) {
            return [];
        }

        $mates = Guest::query()
            ->whereIn('table_name', $own->keys())
            ->where('household_id', '!=', $household->id)
            ->whereHas('household', fn ($query) => $query->where('wedding_id', $household->wedding_id))
            ->orderBy('first_name')
            ->get(['first_name', 'table_name', 'table_note'])
            ->groupBy('table_name');

        return array_values($own->map(fn (Collection $guests, string $table): array => [
            'name' => $table,
            'yours' => array_values($guests->map(fn (Guest $guest): string => $guest->first_name)->all()),
            'mates' => array_values(($mates->get($table) ?? collect())
                ->map(fn (Guest $guest): array => ['name' => $guest->first_name, 'note' => $guest->table_note])
                ->all()),
        ])->all());
    }
}
