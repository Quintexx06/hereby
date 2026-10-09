<?php

namespace App\Http\Requests\Guests;

use App\Enums\Locale;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * One household as the couple edits it: people, language, +1 and events.
 * Events and guest ids must belong to this wedding and household.
 */
class UpdateHouseholdRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Wedding $wedding */
        $wedding = $this->route('wedding');
        /** @var Household $household */
        $household = $this->route('household');

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:191'],
            'locale' => ['required', Rule::enum(Locale::class)],
            'plus_one_allowed' => ['boolean'],
            'events' => ['required', 'array', 'min:1'],
            'events.*' => ['integer', Rule::exists('events', 'id')->where('wedding_id', $wedding->id)],
            'guests' => ['required', 'array', 'min:1', 'max:20'],
            'guests.*.id' => ['nullable', 'integer', Rule::exists('guests', 'id')->where('household_id', $household->id)],
            'guests.*.first_name' => ['required', 'string', 'max:80'],
            'guests.*.last_name' => ['nullable', 'string', 'max:80'],
            'guests.*.is_child' => ['boolean'],
        ];
    }

    /**
     * The validated household, typed for UpdateHousehold.
     *
     * @return array{name: string, email: string|null, locale: string, plus_one_allowed: bool, events: list<int>, guests: list<array{id: int|null, first_name: string, last_name: string|null, is_child: bool}>}
     */
    public function household(): array
    {
        return [
            'name' => $this->string('name')->value(),
            'email' => $this->filled('email') ? $this->string('email')->value() : null,
            'locale' => $this->string('locale')->value(),
            'plus_one_allowed' => $this->boolean('plus_one_allowed'),
            'events' => array_map(intval(...), array_values($this->array('events'))),
            'guests' => array_map(fn (int $index): array => [
                'id' => $this->filled("guests.{$index}.id") ? $this->integer("guests.{$index}.id") : null,
                'first_name' => $this->string("guests.{$index}.first_name")->value(),
                'last_name' => $this->filled("guests.{$index}.last_name") ? $this->string("guests.{$index}.last_name")->value() : null,
                'is_child' => $this->boolean("guests.{$index}.is_child"),
            ], array_keys(array_values($this->array('guests')))),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'events.required' => 'Ladet den Haushalt zu mindestens einem Teil eures Tages ein.',
            'guests.required' => 'Ein Haushalt braucht mindestens eine Person.',
            'guests.*.first_name.required' => 'Jede Person braucht einen Vornamen.',
            'email.email' => 'Diese E-Mail-Adresse sieht nicht richtig aus.',
        ];
    }
}
