<?php

namespace App\Http\Requests\Invitations;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

/**
 * A story for the newspaper: who, a headline, the text, maybe one photo.
 */
class StoreNewspaperArticleRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'author_name' => ['required', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'max:4000'],
            'photo' => ['nullable', File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(config()->integer('hereby.memories.max_kilobytes'))],
        ];
    }
}
