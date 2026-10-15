<?php

namespace App\Actions\Partners;

use App\Actions\Kitchen\BuildKitchenSheet;
use App\Enums\ResponseStatus;
use App\Models\Guest;
use App\Models\Partner;
use App\Models\Wedding;

/**
 * The partner's calendar: every connected wedding by date, with counts and
 * menus only. No guest is named here (CLAUDE.md rule 9).
 */
class BuildPartnerCalendar
{
    public function __construct(private BuildKitchenSheet $sheet) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function handle(Partner $partner): array
    {
        return array_values($partner->weddings()
            ->withCount('households')
            ->orderBy('wedding_date')
            ->get()
            ->map(fn (Wedding $wedding): array => [
                'id' => $wedding->id,
                'couple_names' => $wedding->couple_names,
                'date' => $wedding->wedding_date?->toDateString(),
                'deadline' => $wedding->rsvp_deadline?->toDateString(),
                'open' => $wedding->acceptsReplies(),
                'households' => (int) $wedding->households_count,
                'answered' => $wedding->households()->whereNotNull('responded_at')->count(),
                'attending' => Guest::query()
                    ->whereRelation('household', 'wedding_id', $wedding->id)
                    ->whereHas('responses', fn ($query) => $query->where('status', ResponseStatus::Attending))
                    ->count(),
                'events' => $partner->type->seesFinalNumbers()
                    ? array_map(fn (array $event): array => [
                        'name' => $event['name'] ?? __("invitation.event_types.{$event['type']}", [], 'de_CH'),
                        'attending' => $event['attending'],
                        'menus' => $event['menus'],
                    ], $this->sheet->summary($wedding)['events'])
                    : [],
                'uploads' => $wedding->includesMemories(),
            ])
            ->all());
    }
}
