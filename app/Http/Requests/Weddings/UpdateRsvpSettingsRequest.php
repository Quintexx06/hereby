<?php

namespace App\Http\Requests\Weddings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Which questions guests are asked. Menus are short labels in the couple's
 * own words; `menu_keys` carries the key of a menu being renamed.
 */
class UpdateRsvpSettingsRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'menus' => ['array', 'max:4'],
            'menus.*' => ['nullable', 'string', 'max:80'],
            'menu_keys' => ['array', 'max:4'],
            'menu_keys.*' => ['nullable', 'string', 'max:20'],
            'children_menu' => ['boolean'],
            'offers_shuttle' => ['boolean'],
            'offers_stay' => ['boolean'],
            'asks_song' => ['boolean'],
            'sends_reminders' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'menus.max' => 'Höchstens vier Menüs, damit die Wahl leicht bleibt.',
            'menus.*.max' => 'Haltet die Beschreibung kurz (bis 80 Zeichen).',
        ];
    }

    /**
     * Non-blank menu labels with the key each one had before, if any.
     *
     * @return array{labels: list<string>, keys: list<string|null>, children_menu: bool, offers_shuttle: bool, offers_stay: bool, asks_song: bool, sends_reminders: bool}
     */
    public function settings(): array
    {
        $keys = array_values($this->array('menu_keys'));
        $labels = [];
        $keptKeys = [];

        foreach (array_values($this->array('menus')) as $index => $label) {
            if (is_string($label) && trim($label) !== '') {
                $labels[] = trim($label);
                $keptKeys[] = is_string($keys[$index] ?? null) ? $keys[$index] : null;
            }
        }

        return [
            'labels' => $labels,
            'keys' => $keptKeys,
            'children_menu' => $this->boolean('children_menu'),
            'offers_shuttle' => $this->boolean('offers_shuttle'),
            'offers_stay' => $this->boolean('offers_stay'),
            'asks_song' => $this->boolean('asks_song', true),
            'sends_reminders' => $this->boolean('sends_reminders', true),
        ];
    }
}
