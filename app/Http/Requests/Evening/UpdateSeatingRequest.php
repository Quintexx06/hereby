<?php

namespace App\Http\Requests\Evening;

use App\Models\Wedding;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * «Tischplan»: tables and notes for this wedding's own guests only.
 */
class UpdateSeatingRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Wedding $wedding */
        $wedding = $this->route('wedding');

        return [
            'published' => ['required', 'boolean'],
            'guests' => ['present', 'array'],
            'guests.*.id' => ['required', 'integer', Rule::exists('guests', 'id')->whereIn(
                'household_id', $wedding->households()->select('id'),
            )],
            'guests.*.table_name' => ['nullable', 'string', 'max:40'],
            'guests.*.table_note' => ['nullable', 'string', 'max:160'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['guests.*.id.exists' => 'Diesen Gast gibt es auf eurer Liste nicht.'];
    }
}
