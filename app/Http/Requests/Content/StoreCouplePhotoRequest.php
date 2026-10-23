<?php

namespace App\Http\Requests\Content;

use App\Models\Wedding;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

/**
 * One of the couple's own photos: an image, and never more than eight.
 * Ownership is checked by the route (WeddingPolicy).
 */
class StoreCouplePhotoRequest extends FormRequest
{
    public const int MAX_PHOTOS = 8;

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

                    if ($wedding instanceof Wedding && $wedding->couplePhotos()->reorder()->count() >= self::MAX_PHOTOS) {
                        $fail('Acht Fotos sind das Maximum. Löscht zuerst eines.');
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
            'photo.required' => 'Wählt ein Foto aus.',
            'photo.image' => 'Das ist kein Foto. Möglich sind JPG, PNG und WebP.',
            'photo.mimes' => 'Das ist kein Foto. Möglich sind JPG, PNG und WebP.',
            'photo.max' => 'Das Foto ist zu gross. Höchstens 20 MB.',
            // The server dropped the file before Laravel saw it (PHP upload limit).
            'photo.uploaded' => 'Das Foto ist zu gross für den Upload. Höchstens 20 MB.',
        ];
    }
}
