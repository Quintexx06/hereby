<?php

namespace App\Models;

use Database\Factories\WeddingPhotoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * One of the couple's own photos for their site (at most four; position 0
 * is the cover). Only a web copy is stored, under the wedding's directory
 * on the `memories` disk, so it leaves with the wedding (ADR 0007). Shown
 * to anyone holding its unguessable token, like a personal link.
 *
 * @property int $id
 * @property int $wedding_id
 * @property string $token
 * @property string $path
 * @property int $position
 * @property int $width
 * @property int $height
 * @property int $focus_x Percent from the left that must stay in view.
 * @property int $focus_y Percent from the top that must stay in view.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class WeddingPhoto extends Model
{
    /** @use HasFactory<WeddingPhotoFactory> */
    use HasFactory;

    public const string DISK = GuestPhoto::DISK;

    public const int TOKEN_LENGTH = 40;

    /** The lighter widths a guest page may ask for (`?w=`). */
    public const array WIDTHS = [800, 1200];

    /** @var list<string> */
    protected $fillable = ['position', 'focus_x', 'focus_y'];

    /** @var array<string, int> */
    protected $attributes = ['focus_x' => 50, 'focus_y' => 50];

    /**
     * Where a template's crop centres: [x, y] in percent.
     *
     * @return array{int, int}
     */
    public function focus(): array
    {
        return [$this->focus_x, $this->focus_y];
    }

    /**
     * @return BelongsTo<Wedding, $this>
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    /**
     * Where the site loads it from, by token.
     */
    public function url(): string
    {
        return route('wedding-photos.show', $this->token);
    }

    /**
     * The web copy and its lighter widths, for `srcset`: a phone loads the
     * 800 or 1200 pixel WebP instead of the full copy (rule 2). Null for the
     * stand-ins of samples and demos, which are never stored.
     */
    public function srcset(): ?string
    {
        if (! $this->exists) {
            return null;
        }

        return collect(self::WIDTHS)
            ->filter(fn (int $width): bool => $width < $this->width)
            ->map(fn (int $width): string => route('wedding-photos.show', [$this->token, 'w' => $width])." {$width}w")
            ->push("{$this->url()} {$this->width}w")
            ->implode(', ');
    }

    /**
     * Where the WebP copy of a width is kept, beside the web copy.
     */
    public function variantPath(int $width): string
    {
        return (string) preg_replace('/\.jpg$/', "-{$width}.webp", $this->path);
    }

    public static function directoryFor(int $weddingId): string
    {
        return GuestPhoto::directoryFor($weddingId).'/couple';
    }

    protected static function booted(): void
    {
        static::creating(function (WeddingPhoto $photo): void {
            $photo->token ??= Str::random(self::TOKEN_LENGTH);
        });

        static::deleting(function (WeddingPhoto $photo): void {
            Storage::disk(self::DISK)->delete([
                $photo->path,
                ...array_map($photo->variantPath(...), self::WIDTHS),
            ]);
        });
    }
}
