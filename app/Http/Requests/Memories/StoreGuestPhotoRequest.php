<?php

namespace App\Http\Requests\Memories;

use App\Models\Wedding;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

/**
 * One photo from a guest. No account: the link's token is the credential,
 * checked by the route. Photos only, and never more than a wedding may hold.
 */
class StoreGuestPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'photo' => [
                'required',
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(config()->integer('hereby.memories.max_kilobytes')),
                function (string $attribute, mixed $value, Closure $fail): void {
                    $wedding = $this->route('wedding');

                    if ($wedding instanceof Wedding && $wedding->photos()->count() >= config()->integer('hereby.memories.max_photos')) {
                        $fail(__('memories.full'));
                    }
                },
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'photo.*' => __('memories.invalid'),
        ];
    }
}
