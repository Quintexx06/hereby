<?php

namespace App\Http\Requests\Guests;

use App\Enums\Locale;
use App\Guests\Import\GuestListReader;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Households to save: the confirmed import preview, or one added by hand.
 */
class StoreHouseholdsRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'households' => ['required', 'array', 'min:1', 'max:'.GuestListReader::MAX_ROWS],
            'households.*.name' => ['required', 'string', 'max:120'],
            'households.*.email' => ['nullable', 'email', 'max:191'],
            'households.*.locale' => ['nullable', Rule::enum(Locale::class)],
            'households.*.plus_one_allowed' => ['boolean'],
            'households.*.guests' => ['required', 'array', 'min:1', 'max:20'],
            'households.*.guests.*.first_name' => ['required', 'string', 'max:80'],
            'households.*.guests.*.last_name' => ['nullable', 'string', 'max:80'],
            'households.*.guests.*.is_child' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'households.*.name.required' => 'Jeder Haushalt braucht einen Namen.',
            'households.*.guests.required' => 'Jeder Haushalt braucht mindestens eine Person.',
            'households.*.guests.*.first_name.required' => 'Jede Person braucht einen Vornamen.',
            'households.*.email.email' => 'Diese E-Mail-Adresse sieht nicht richtig aus.',
        ];
    }
}
