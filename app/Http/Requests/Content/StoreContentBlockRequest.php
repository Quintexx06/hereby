<?php

namespace App\Http\Requests\Content;

use App\Enums\ContentBlockType;
use App\Models\Wedding;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Adding a block: one of each type per wedding.
 */
class StoreContentBlockRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Wedding $wedding */
        $wedding = $this->route('wedding');

        return [
            'type' => ['required', Rule::enum(ContentBlockType::class),
                Rule::unique('content_blocks', 'type')->where('wedding_id', $wedding->id)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['type.unique' => 'Diesen Block habt ihr schon.'];
    }
}
