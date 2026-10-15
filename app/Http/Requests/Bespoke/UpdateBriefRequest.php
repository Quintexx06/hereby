<?php

namespace App\Http\Requests\Bespoke;

use App\Models\Wedding;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * The couple's brief for «Massanfertigung», saved as they write it.
 */
class UpdateBriefRequest extends FormRequest
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
            'wishes' => ['nullable', 'string', 'max:4000'],
            'colors' => ['nullable', 'array', 'max:3'],
            'colors.*' => ['regex:/^#[0-9a-fA-F]{6}$/'],
            'mood' => ['nullable', 'string', 'max:300'],
            'links' => ['nullable', 'string', 'max:2000'],
            'ready_by' => ['nullable', 'date', 'after:today'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'colors.max' => 'Höchstens drei Farben, bitte.',
            'ready_by.after' => 'Das Datum sollte in der Zukunft liegen.',
            'wishes.max' => 'Das ist zu lang (bis 4000 Zeichen).',
        ];
    }
}
