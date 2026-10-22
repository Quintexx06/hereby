<?php

namespace App\Http\Requests\Admin;

use App\Enums\PartnerType;
use App\Models\Partner;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * The team corrects a partner: name, kind, contact, street, sign-in, Showcase.
 * Every field is optional, so the Showcase switch can send just itself.
 */
class UpdatePartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $partner = $this->route('partner');
        $login = $partner instanceof Partner ? $partner->users()->value('id') : null;

        return [
            'type' => ['sometimes', Rule::enum(PartnerType::class)],
            'name' => ['sometimes', 'required', 'string', 'max:120'],
            'login' => ['sometimes', 'required', 'email', 'max:191', Rule::unique('users', 'email')->ignore($login)],
            'email' => ['sometimes', 'nullable', 'email', 'max:191'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:40'],
            'website' => ['sometimes', 'nullable', 'url', 'max:191'],
            'address' => ['sometimes', 'nullable', 'string', 'max:200'],
            'postcode' => ['sometimes', 'nullable', 'regex:/^\d{4}$/'],
            'town' => ['sometimes', 'nullable', 'string', 'max:80'],
            'pitch_note' => ['sometimes', 'nullable', 'string', 'max:500'],
            'is_showcase' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Wie heisst der Partner?',
            'login.unique' => 'Mit dieser Adresse gibt es schon ein Konto.',
            'website.url' => 'Bitte mit https:// davor.',
            'postcode.regex' => 'Eine Schweizer Postleitzahl hat vier Ziffern.',
        ];
    }
}
