<?php

namespace App\Http\Requests\Evening;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * «Heimweg»: up to six connections, each with a name and a time of night.
 */
class UpdateWayHomeRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'connections' => ['present', 'array', 'max:6'],
            'connections.*.label' => ['required', 'string', 'max:60'],
            'connections.*.place' => ['nullable', 'string', 'max:60'],
            'connections.*.time' => ['required', 'date_format:H:i'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'connections.*.label.required' => 'Wie heisst diese Verbindung?',
            'connections.*.time.date_format' => 'Bitte eine Uhrzeit wie 00:40.',
        ];
    }

    /**
     * @return list<array{label: string, place: string|null, time: string}>
     */
    public function connections(): array
    {
        /** @var list<array{label: string, place?: string|null, time: string}> $connections */
        $connections = $this->validated('connections');

        return array_map(fn (array $connection): array => [
            'label' => trim($connection['label']),
            'place' => filled($connection['place'] ?? null) ? trim((string) $connection['place']) : null,
            'time' => $connection['time'],
        ], $connections);
    }
}
