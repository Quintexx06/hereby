<?php

namespace App\Models;

use Database\Factories\NewspaperArticleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * One story for the wedding newspaper (spec 2026-10-13). Its photo sits on
 * the private memories disk and leaves with the article or the wedding.
 *
 * @property int $id
 * @property int $wedding_id
 * @property string $author_name
 * @property string $title
 * @property string $body
 * @property string|null $photo_path
 * @property int $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['author_name', 'title', 'body'])]
class NewspaperArticle extends Model
{
    /** @use HasFactory<NewspaperArticleFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Wedding, $this>
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    protected static function booted(): void
    {
        static::deleted(function (self $article): void {
            if ($article->photo_path !== null) {
                Storage::disk(GuestPhoto::DISK)->delete($article->photo_path);
            }
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['position' => 'integer'];
    }
}
