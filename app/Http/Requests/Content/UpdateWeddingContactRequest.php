<?php

namespace App\Http\Requests\Content;

use App\Models\Wedding;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * The footer contact on «Inhalte»: a name, and a mail address or phone.
 */
class UpdateWeddingContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        $wedding = $this->route('wedding');

        return $wedding instanceof Wedding && $this->user()?->can('update', $wedding) === true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'contact_name' => ['nullable', 'string', 'max:80', 'required_with:contact_email,contact_phone'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:40', 'regex:/^[+0-9 ()\/.-]{6,}$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contact_name.required_with' => 'Wer ist das? Ein Name, damit eure Gäste wissen, wem sie schreiben.',
            'contact_email.email' => 'Diese Adresse sieht nicht nach einer E-Mail aus.',
            'contact_phone.regex' => 'Nur Ziffern, Leerzeichen und + bitte.',
        ];
    }
}
