<?php

namespace App\Models;

use App\Enums\ContentBlockType;
use Database\Factories\ContentBlockFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * One block on the invitation, with its text per language.
 *
 * @property int $id
 * @property int $wedding_id
 * @property ContentBlockType $type
 * @property int|null $event_id Null: every household sees it.
 * @property int $position
 * @property array<string, array{title?: string|null, body?: string|null, items?: list<array{question: string, answer: string, url?: string}>}> $content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['type', 'event_id', 'position', 'content'])]
class ContentBlock extends Model
{
    /** @use HasFactory<ContentBlockFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Wedding, $this>
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    /**
     * The text in one language, or in the fallback when that one is empty.
     * Null when neither has anything to show.
     *
     * @return array{title: string|null, body: string|null, items: list<array{question: string, answer: string, url?: string}>}|null
     */
    public function textIn(string $locale, string $fallback): ?array
    {
        foreach (array_unique([$locale, $fallback]) as $candidate) {
            $text = $this->content[$candidate] ?? [];
            $body = trim((string) ($text['body'] ?? ''));
            $items = array_values(array_filter($text['items'] ?? [], fn (array $item): bool => trim($item['question']) !== ''));

            if ($body !== '' || $items !== []) {
                return [
                    'title' => filled($text['title'] ?? null) ? trim((string) $text['title']) : null,
                    'body' => $body !== '' ? $body : null,
                    'items' => $items,
                ];
            }
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => ContentBlockType::class,
            'content' => 'array',
        ];
    }
}
