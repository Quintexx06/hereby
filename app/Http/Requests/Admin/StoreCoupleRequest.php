<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * The team sets up a wedding for a couple: who they are and where to write.
 */
class StoreCoupleRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'partner_one' => ['required', 'string', 'max:60'],
            'partner_two' => ['required', 'string', 'max:60'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Mit dieser Adresse gibt es schon ein Konto.',
            'partner_one.required' => 'Beide Vornamen, bitte.',
            'partner_two.required' => 'Beide Vornamen, bitte.',
        ];
    }
}
