<?php

namespace App\Actions\Kitchen;

use App\Enums\EventType;
use App\Enums\ResponseStatus;
use App\Models\Event;
use App\Models\EventResponse;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Support\Collection;

/**
 * The numbers the venue needs (roadmap 1.12): per part of the day who comes,
 * children, unanswered and menus; household totals; and, for the printable
 * sheet and the export only, who has allergies.
 */
class BuildKitchenSheet
{
    /**
     * @return array{events: list<array{id: int, type: string, name: string|null, starts_at: string, attending: int, children: int, pending: int, menus: list<array{label: string, count: int}>}>, allergies: int, shuttle: int, stays: int, songs: list<string>}
     */
    public function summary(Wedding $wedding): array
    {
        $households = $this->households($wedding);
        $guests = $households->flatMap(fn (Household $household) => $household->guests);

        return [
            'events' => array_values($wedding->events()->orderBy('starts_at')->get()
                ->map(fn (Event $event): array => $this->event($wedding, $event, $households))->all()),
            'allergies' => $guests->filter(fn (Guest $guest): bool => $guest->dietary_notes !== null)->count(),
            'shuttle' => (int) $households->sum('shuttle_seats'),
            'stays' => $households->where('needs_stay', true)->count(),
            'songs' => array_values(array_filter($households->map(fn (Household $household): ?string => $household->song_wish)->all())),
        ];
    }

    /**
     * Server-rendered documents only: never send this through Inertia.
     *
     * @return list<array{name: string, household: string, child: bool, parts: list<string>, notes: string}>
     */
    public function allergies(Wedding $wedding): array
    {
        $events = $wedding->events()->orderBy('starts_at')->get()->keyBy('id');

        return array_values($this->households($wedding)
            ->flatMap(fn (Household $household) => $household->guests
                ->filter(fn (Guest $guest): bool => $guest->dietary_notes !== null)
                ->map(fn (Guest $guest): array => [
                    'name' => trim($guest->first_name.' '.$guest->last_name),
                    'household' => $household->name,
                    'child' => $guest->is_child,
                    'parts' => array_values($guest->responses
                        ->filter(fn (EventResponse $response): bool => $response->status === ResponseStatus::Attending)
                        ->map(fn (EventResponse $response): string => $this->eventName($events->get($response->event_id)))
                        ->all()),
                    'notes' => (string) $guest->dietary_notes,
                ]))
            ->all());
    }

    /**
     * @return Collection<int, Household>
     */
    public function households(Wedding $wedding): Collection
    {
        return $wedding->households()->with(['guests.responses', 'events:id'])->orderBy('name')->get();
    }

    public function eventName(?Event $event): string
    {
        return $event ? ($event->name ?? (string) __("invitation.event_types.{$event->type->value}", [], 'de_CH')) : '';
    }

    /**
     * Menu labels by key, children's menu first, in the couple's words.
     *
     * @return array<string, string>
     */
    public function menuLabels(Wedding $wedding): array
    {
        return ($wedding->children_menu ? ['children' => 'Kindermenü'] : [])
            + collect($wedding->menu_options ?? [])->pluck('label', 'key')->all();
    }

    /**
     * @param  Collection<int, Household>  $households
     * @return array{id: int, type: string, name: string|null, starts_at: string, attending: int, children: int, pending: int, menus: list<array{label: string, count: int}>}
     */
    private function event(Wedding $wedding, Event $event, Collection $households): array
    {
        $invited = $households->filter(fn (Household $household): bool => $household->events->contains('id', $event->id))
            ->flatMap(fn (Household $household) => $household->guests);
        $answer = fn (Guest $guest): ?EventResponse => $guest->responses->firstWhere('event_id', $event->id);
        $attending = $invited->filter(fn (Guest $guest): bool => $answer($guest)?->status === ResponseStatus::Attending);
        $menus = $event->type === EventType::Dinner ? $this->menuLabels($wedding) : [];

        return [
            'id' => $event->id,
            'type' => $event->type->value,
            'name' => $event->name,
            'starts_at' => $event->starts_at->toIso8601String(),
            'attending' => $attending->count(),
            'children' => $attending->where('is_child', true)->count(),
            'pending' => $invited->filter(fn (Guest $guest): bool => in_array($answer($guest)?->status, [null, ResponseStatus::Pending], true))->count(),
            'menus' => array_map(fn (string $label, string $key): array => [
                'label' => $label,
                'count' => $attending->filter(fn (Guest $guest): bool => $answer($guest)?->menu_choice === $key)->count(),
            ], $menus, array_keys($menus)),
        ];
    }
}
