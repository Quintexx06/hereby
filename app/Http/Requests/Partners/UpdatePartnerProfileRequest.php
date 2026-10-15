<?php

namespace App\Http\Requests\Partners;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * A partner's profile; the venue fields only matter for venues.
 */
class UpdatePartnerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->partner !== null;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:191'],
            'phone' => ['nullable', 'string', 'max:40', 'regex:/^[+0-9 ()\/.-]{6,}$/'],
            'website' => ['nullable', 'url:https,http', 'max:255'],
            'address' => ['nullable', 'string', 'max:200'],
            'postcode' => ['nullable', 'regex:/^\d{4}$/'],
            'town' => ['nullable', 'string', 'max:80'],
            'capacity' => ['nullable', 'integer', 'between:1,5000'],
            'about' => ['nullable', 'string', 'max:2000'],
            'travel' => ['nullable', 'string', 'max:3000'],
            'stay' => ['nullable', 'string', 'max:3000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'postcode.regex' => 'Eine Schweizer Postleitzahl hat vier Ziffern.',
            'website.url' => 'Bitte mit https:// davor.',
            'phone.regex' => 'Nur Ziffern, Leerzeichen und + bitte.',
        ];
    }
}
