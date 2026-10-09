<?php

namespace App\Http\Requests\Content;

use App\Models\Wedding;
use App\Support\WeddingLanguages;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * A block's text per language and who sees it. Only the wedding's own
 * languages are kept; empty FAQ rows are dropped.
 */
class UpdateContentBlockRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => ['nullable', 'integer', Rule::exists('events', 'id')->where('wedding_id', $this->wedding()->id)],
            'content' => ['present', 'array'],
            'content.*.title' => ['nullable', 'string', 'max:80'],
            'content.*.body' => ['nullable', 'string', 'max:3000'],
            'content.*.items' => ['nullable', 'array', 'max:12'],
            'content.*.items.*.question' => ['nullable', 'string', 'max:160'],
            'content.*.items.*.answer' => ['nullable', 'string', 'max:1000'],
            'content.*.items.*.url' => ['nullable', 'url:https,http', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'event_id.exists' => 'Diesen Teil gibt es in eurem Ablauf nicht.',
            'content.*.title.max' => 'Haltet den Titel kurz (bis 80 Zeichen).',
            'content.*.body.max' => 'Das ist zu lang (bis 3000 Zeichen).',
            'content.*.items.max' => 'Höchstens zwölf Einträge.',
            'content.*.items.*.url.url' => 'Bitte eine vollständige Adresse, z. B. https://hotel.ch.',
        ];
    }

    /**
     * @return array<string, array{title: string|null, body: string|null, items: list<array{question: string, answer: string, url?: string}>}>
     */
    public function content(): array
    {
        $content = [];

        foreach (WeddingLanguages::of($this->wedding()) as $locale) {
            $text = $this->input("content.{$locale}");

            if (! is_array($text)) {
                continue;
            }

            $items = array_values(array_filter(array_map(fn (mixed $item): array => [
                'question' => trim((string) data_get($item, 'question', '')),
                'answer' => trim((string) data_get($item, 'answer', '')),
                ...(filled(data_get($item, 'url')) ? ['url' => trim((string) data_get($item, 'url'))] : []),
            ], is_array($text['items'] ?? null) ? $text['items'] : []), fn (array $item): bool => $item['question'] !== ''));

            $content[$locale] = [
                'title' => filled($text['title'] ?? null) ? trim((string) $text['title']) : null,
                'body' => filled($text['body'] ?? null) ? trim((string) $text['body']) : null,
                'items' => $items,
            ];
        }

        return $content;
    }

    private function wedding(): Wedding
    {
        /** @var Wedding $wedding */
        $wedding = $this->route('wedding');

        return $wedding;
    }
}
