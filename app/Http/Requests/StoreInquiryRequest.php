<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * `website` is a honeypot: hidden from people, filled in by bots.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc', 'max:160'],
            'question' => ['required', 'string', 'min:10', 'max:1500'],
            'website' => ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Wohin dürfen wir antworten?',
            'email.email' => 'Bitte prüft die E-Mail-Adresse.',
            'question.required' => 'Was möchtet ihr wissen?',
            'question.min' => 'Erzählt uns ein bisschen mehr, damit wir gut antworten können.',
        ];
    }
}
