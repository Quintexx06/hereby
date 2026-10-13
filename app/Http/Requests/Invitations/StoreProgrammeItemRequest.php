<?php

namespace App\Http\Requests\Invitations;

use App\Enums\ProgrammeKind;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * A guest's offer for the evening: what, how long, what it needs.
 */
class StoreProgrammeItemRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kind' => ['required', Rule::enum(ProgrammeKind::class)],
            'title' => ['required', 'string', 'max:120'],
            'minutes' => ['required', 'integer', 'min:1', 'max:60'],
            'contact_name' => ['required', 'string', 'max:80'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
