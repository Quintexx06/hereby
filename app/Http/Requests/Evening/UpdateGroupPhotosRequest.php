<?php

namespace App\Http\Requests\Evening;

use App\Models\Wedding;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * «Gruppenfotos»: the whole list at once, with this wedding's households only.
 */
class UpdateGroupPhotosRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Wedding $wedding */
        $wedding = $this->route('wedding');

        return [
            'groups' => ['present', 'array', 'max:40'],
            'groups.*.id' => ['nullable', 'integer', Rule::exists('group_photos', 'id')->where('wedding_id', $wedding->id)],
            'groups.*.title' => ['required', 'string', 'max:80'],
            'groups.*.place' => ['nullable', 'string', 'max:80'],
            'groups.*.household_ids' => ['present', 'array'],
            'groups.*.household_ids.*' => ['integer', Rule::exists('households', 'id')->where('wedding_id', $wedding->id)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'groups.*.title.required' => 'Wie heisst dieses Foto?',
            'groups.*.household_ids.*.exists' => 'Diesen Haushalt gibt es auf eurer Liste nicht.',
        ];
    }
}
