<?php

namespace App\Actions\Weddings;

use App\Enums\ReplyStatus;
use App\Enums\ResponseStatus;
use App\Models\Event;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Everything the dashboard shows for an active wedding: counts, reply
 * status, headcount per event, and the next actions ("Heute für euch").
 */
class BuildWeddingOverview
{
    /**
     * @return array<string, mixed>
     */
    public function handle(Wedding $wedding): array
    {
        $households = $this->households($wedding);
        $replies = $households->countBy(fn (Household $household): string => $household->replyStatus()->value);
        $daysLeft = $wedding->wedding_date ? (int) now()->startOfDay()->diffInDays($wedding->wedding_date, false) : null;
        $deadlineDays = $wedding->rsvp_deadline ? (int) now()->startOfDay()->diffInDays($wedding->rsvp_deadline, false) : null;

        return [
            'days_left' => $daysLeft,
            'deadline_days' => $deadlineDays,
            'households' => $households->count(),
            'guests' => (int) $households->sum('guests_count'),
            'replies' => collect(ReplyStatus::cases())->mapWithKeys(fn (ReplyStatus $status): array => [$status->value => $replies[$status->value] ?? 0]),
            'events' => $this->events($wedding),
            'actions' => $this->actions($wedding, $households->count(), $replies->all(), $deadlineDays),
        ];
    }

    /**
     * @return Collection<int, Household>
     */
    public function households(Wedding $wedding): Collection
    {
        return $wedding->households()
            ->withCount('guests')
            ->withExists(['guests as has_answered' => fn (Builder $query) => $query
                ->whereHas('responses', fn (Builder $responses) => $responses->where('status', '!=', ResponseStatus::Pending))])
            ->orderBy('name')
            ->get();
    }

    /**
     * @return list<array{id: int, type: string, name: string|null, starts_at: string, invited: int, attending: int}>
     */
    private function events(Wedding $wedding): array
    {
        return array_values($wedding->events()
            ->withCount([
                'households as invited',
                'responses as attending' => fn (Builder $query) => $query->where('status', ResponseStatus::Attending),
            ])
            ->get()
            ->map(fn (Event $event): array => [
                'id' => $event->id,
                'type' => $event->type->value,
                'name' => $event->name,
                'starts_at' => $event->starts_at->toIso8601String(),
                'invited' => (int) $event->invited,
                'attending' => (int) $event->attending,
            ])
            ->all());
    }

    /**
     * At most three things that need the couple today, most urgent first.
     *
     * @param  array<string, int>  $replies
     * @return list<array{key: string, count?: int}>
     */
    private function actions(Wedding $wedding, int $households, array $replies, ?int $deadlineDays): array
    {
        $actions = [];

        if ($households === 0) {
            $actions[] = ['key' => 'import_guests'];
        }

        if (($replies[ReplyStatus::NeverOpened->value] ?? 0) > 0) {
            $actions[] = ['key' => 'share_links', 'count' => $replies[ReplyStatus::NeverOpened->value]];
        }

        if ($deadlineDays !== null && $deadlineDays <= 14 && ($replies[ReplyStatus::Opened->value] ?? 0) > 0) {
            $actions[] = ['key' => 'nudge_opened', 'count' => $replies[ReplyStatus::Opened->value]];
        }

        if (! $wedding->rsvp_deadline) {
            $actions[] = ['key' => 'set_deadline'];
        }

        return array_slice($actions, 0, 3);
    }
}
