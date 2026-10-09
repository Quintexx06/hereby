<?php

namespace App\Actions\Invitations;

use App\Enums\EventType;
use App\Enums\ResponseStatus;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Support\Facades\DB;

/**
 * Stores one household's reply: an answer per person and event, menus at the
 * dinner, allergies (write-only), the plus-one, and the household questions
 * the couple asks. Saving again replaces the previous answer.
 */
class SaveReply
{
    /**
     * @param  array{answers: list<array{guest_id: int, event_id: int, status: string, menu: string|null}>, guests: list<array{id: int, dietary_notes: string|null, clear_dietary: bool}>, plus_one: array{first_name: string, menu: string|null, dietary_notes: string|null, clear_dietary: bool}|null, shuttle_seats: int|null, needs_stay: bool|null, song_wish: string|null}  $data
     */
    public function handle(Household $household, array $data): void
    {
        $dinners = array_values(array_map(intval(...), $household->events->filter(fn (Event $event): bool => $event->type === EventType::Dinner)->modelKeys()));

        DB::transaction(function () use ($household, $data, $dinners): void {
            foreach ($data['answers'] as $answer) {
                $this->answer($household->wedding, $household->guests->firstWhere('id', $answer['guest_id']), $answer['event_id'], $answer['status'], $answer['menu'], $dinners);
            }

            foreach ($data['guests'] as $notes) {
                $this->dietaryNotes($household->guests->firstWhere('id', $notes['id']), $notes);
            }

            $this->plusOne($household, $data, $dinners);
            $this->householdQuestions($household, $data);
        });
    }

    /**
     * @param  list<int>  $dinners
     */
    private function answer(Wedding $wedding, ?Guest $guest, int $eventId, string $status, ?string $menu, array $dinners): void
    {
        if (! $guest) {
            return;
        }

        $attending = $status === ResponseStatus::Attending->value;

        $guest->responses()->updateOrCreate(['event_id' => $eventId], [
            'status' => $status,
            'menu_choice' => $attending && in_array($eventId, $dinners, true) && $wedding->menuKeys($guest->is_child) !== [] ? $menu : null,
            'responded_at' => now(),
        ]);
    }

    /**
     * Blank keeps what was stored: the field is never sent back to the browser.
     *
     * @param  array{dietary_notes: string|null, clear_dietary: bool}  $input
     */
    private function dietaryNotes(?Guest $guest, array $input): void
    {
        if (! $guest) {
            return;
        }

        if ($input['clear_dietary']) {
            $guest->update(['dietary_notes' => null]);
        } elseif ($input['dietary_notes'] !== null && trim($input['dietary_notes']) !== '') {
            $guest->update(['dietary_notes' => trim($input['dietary_notes'])]);
        }
    }

    /**
     * The plus-one joins every part the household attends, with their own menu.
     *
     * @param  array{answers: list<array{guest_id: int, event_id: int, status: string, menu: string|null}>, plus_one: array{first_name: string, menu: string|null, dietary_notes: string|null, clear_dietary: bool}|null}  $data
     * @param  list<int>  $dinners
     */
    private function plusOne(Household $household, array $data, array $dinners): void
    {
        $existing = $household->guests->firstWhere('is_plus_one', true);
        $input = $data['plus_one'];

        if ($input === null) {
            $existing?->delete();

            return;
        }

        $plusOne = $existing ?? $household->guests()->make(['is_plus_one' => true, 'is_child' => false]);
        $plusOne->fill(['first_name' => $input['first_name'], 'is_plus_one' => true])->save();

        foreach ($household->events as $event) {
            $joins = collect($data['answers'])->contains(fn (array $answer): bool => $answer['event_id'] === $event->id
                && $answer['status'] === ResponseStatus::Attending->value);

            $this->answer($household->wedding, $plusOne, $event->id, $joins ? 'attending' : 'declined', $input['menu'], $dinners);
        }

        $this->dietaryNotes($plusOne, $input);
    }

    /**
     * @param  array{shuttle_seats: int|null, needs_stay: bool|null, song_wish: string|null}  $data
     */
    private function householdQuestions(Household $household, array $data): void
    {
        $wedding = $household->wedding;

        $household->forceFill(array_filter([
            'shuttle_seats' => $wedding->offers_shuttle ? $data['shuttle_seats'] : false,
            'needs_stay' => $wedding->offers_stay ? $data['needs_stay'] : false,
            'song_wish' => $wedding->asks_song ? $data['song_wish'] : false,
        ], fn (mixed $value): bool => $value !== false) + ['responded_at' => now()])->save();
    }
}
