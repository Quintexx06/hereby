<?php

namespace App\Http\Requests\Guests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * A pasted list or an uploaded file to read before importing.
 */
class PreviewGuestImportRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => ['required_without:file', 'nullable', 'string', 'max:200000'],
            'file' => ['required_without:text', 'nullable', 'file', 'max:2048', 'extensions:xlsx,csv,tsv,txt,vcf'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'text.required_without' => 'Fügt eure Liste ein oder wählt eine Datei.',
            'file.extensions' => 'Wir lesen Excel (.xlsx), CSV, TXT und Kontakte (.vcf).',
            'file.max' => 'Die Datei ist grösser als 2 MB.',
        ];
    }
}
