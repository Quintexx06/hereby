<?php

namespace App\Http\Requests\Invitations;

use App\Enums\EventType;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Household;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * One household's reply. The token is the credential, so ids are scoped to
 * this household and its events, and completeness and menus are checked
 * against what the couple actually asks (spec: 2026-10-09-rsvp-design).
 */
class SaveReplyRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $household = $this->household();

        return [
            'answers' => ['required', 'array', 'max:400'],
            'answers.*.guest_id' => ['required', 'integer', Rule::exists('guests', 'id')
                ->where('household_id', $household->id)->where('is_plus_one', false)],
            'answers.*.event_id' => ['required', 'integer', Rule::exists('event_household', 'event_id')
                ->where('household_id', $household->id)],
            'answers.*.status' => ['required', Rule::in(['attending', 'declined'])],
            'answers.*.menu' => ['nullable', 'string', 'max:40'],
            'guests' => ['array', 'max:20'],
            'guests.*.id' => ['required', 'integer', Rule::exists('guests', 'id')->where('household_id', $household->id)],
            'guests.*.dietary_notes' => ['nullable', 'string', 'max:500'],
            'guests.*.clear_dietary' => ['boolean'],
            'plus_one' => [Rule::prohibitedIf(! $household->plus_one_allowed), 'nullable', 'array'],
            'plus_one.first_name' => ['required_with:plus_one', 'string', 'max:80'],
            'plus_one.menu' => ['nullable', 'string', 'max:40'],
            'plus_one.dietary_notes' => ['nullable', 'string', 'max:500'],
            'plus_one.clear_dietary' => ['boolean'],
            'shuttle_seats' => ['nullable', 'integer', 'min:0', 'max:20'],
            'needs_stay' => ['nullable', 'boolean'],
            'song_wish' => ['nullable', 'string', 'max:160'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'plus_one.prohibited' => __('rsvp.errors.no_plus_one'),
            'plus_one.first_name.required_with' => __('rsvp.errors.plus_one_name'),
            'guests.*.dietary_notes.max' => __('rsvp.errors.too_long'),
            'song_wish.max' => __('rsvp.errors.too_long'),
        ];
    }

    /**
     * @return list<callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (! $this->household()->wedding->acceptsReplies()) {
                    $validator->errors()->add('deadline', __('rsvp.errors.closed'));

                    return;
                }

                if ($validator->errors()->isEmpty()) {
                    $this->checkEveryoneAnswered($validator);
                    $this->checkMenus($validator);
                }
            },
        ];
    }

    /**
     * The validated reply, typed for SaveReply.
     *
     * @return array{answers: list<array{guest_id: int, event_id: int, status: string, menu: string|null}>, guests: list<array{id: int, dietary_notes: string|null, clear_dietary: bool}>, plus_one: array{first_name: string, menu: string|null, dietary_notes: string|null, clear_dietary: bool}|null, shuttle_seats: int|null, needs_stay: bool|null, song_wish: string|null}
     */
    public function reply(): array
    {
        $text = fn (mixed $value): ?string => is_string($value) && $value !== '' ? $value : null;

        return [
            'answers' => array_values(array_map(fn (mixed $answer): array => [
                'guest_id' => (int) data_get($answer, 'guest_id'),
                'event_id' => (int) data_get($answer, 'event_id'),
                'status' => (string) data_get($answer, 'status'),
                'menu' => $text(data_get($answer, 'menu')),
            ], $this->array('answers'))),
            'guests' => array_values(array_map(fn (mixed $guest): array => [
                'id' => (int) data_get($guest, 'id'),
                'dietary_notes' => $text(data_get($guest, 'dietary_notes')),
                'clear_dietary' => (bool) data_get($guest, 'clear_dietary', false),
            ], $this->array('guests'))),
            'plus_one' => $this->filled('plus_one') ? [
                'first_name' => $this->string('plus_one.first_name')->trim()->value(),
                'menu' => $text($this->input('plus_one.menu')),
                'dietary_notes' => $text($this->input('plus_one.dietary_notes')),
                'clear_dietary' => $this->boolean('plus_one.clear_dietary'),
            ] : null,
            'shuttle_seats' => $this->filled('shuttle_seats') ? $this->integer('shuttle_seats') : null,
            'needs_stay' => $this->has('needs_stay') && $this->input('needs_stay') !== null ? $this->boolean('needs_stay') : null,
            'song_wish' => $text($this->string('song_wish')->trim()->value()),
        ];
    }

    public function household(): Household
    {
        /** @var Household $household */
        $household = $this->route('household');

        return $household;
    }

    private function checkEveryoneAnswered(Validator $validator): void
    {
        $household = $this->household();
        $given = collect($this->array('answers'))
            ->map(fn (mixed $answer): string => data_get($answer, 'guest_id').':'.data_get($answer, 'event_id'))
            ->unique();
        $expected = $household->guests->where('is_plus_one', false)->count() * $household->events->count();

        if ($given->count() < $expected) {
            $validator->errors()->add('answers', __('rsvp.errors.incomplete'));
        }
    }

    /**
     * A menu is asked of attending people at the dinner, from the options that
     * apply to them; anywhere else a menu is ignored.
     */
    private function checkMenus(Validator $validator): void
    {
        $household = $this->household();
        $wedding = $household->wedding;
        $guests = $household->guests->keyBy('id');
        $dinners = array_values(array_map(intval(...), $household->events->filter(fn (Event $event): bool => $event->type === EventType::Dinner)->modelKeys()));

        foreach ($this->array('answers') as $index => $answer) {
            /** @var Guest|null $guest */
            $guest = $guests->get(data_get($answer, 'guest_id'));

            if (! $guest || data_get($answer, 'status') !== 'attending' || ! in_array((int) data_get($answer, 'event_id'), $dinners, true)) {
                continue;
            }

            $this->checkMenu($validator, "answers.{$index}.menu", data_get($answer, 'menu'), $wedding->menuKeys($guest->is_child));
        }

        if ($this->filled('plus_one') && $dinners !== [] && $this->plusOneAttendsDinner($dinners)) {
            $this->checkMenu($validator, 'plus_one.menu', $this->input('plus_one.menu'), $wedding->menuKeys());
        }
    }

    /**
     * @param  list<string>  $allowed
     */
    private function checkMenu(Validator $validator, string $key, mixed $menu, array $allowed): void
    {
        if ($allowed === []) {
            return;
        }

        if (blank($menu)) {
            $validator->errors()->add($key, __('rsvp.errors.menu'));
        } elseif (! in_array($menu, $allowed, true)) {
            $validator->errors()->add($key, __('rsvp.errors.menu_invalid'));
        }
    }

    /**
     * @param  list<int>  $dinners
     */
    private function plusOneAttendsDinner(array $dinners): bool
    {
        return collect($this->array('answers'))->contains(fn (mixed $answer): bool => data_get($answer, 'status') === 'attending'
            && in_array((int) data_get($answer, 'event_id'), $dinners, true));
    }
}
