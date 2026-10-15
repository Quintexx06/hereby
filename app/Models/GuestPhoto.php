<?php

namespace App\Models;

use Database\Factories\GuestPhotoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * A photo a guest sent through the wedding's upload link. Guest data: the
 * file is private, shown only to the couple, and deleted with the wedding.
 *
 * @property int $id
 * @property int $wedding_id
 * @property int|null $partner_id Set when the wedding's photographer uploaded it.
 * @property string $path The original, on the `memories` disk.
 * @property string|null $thumbnail_path A small JPEG for the gallery, once made.
 * @property int $size Bytes of the original.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class GuestPhoto extends Model
{
    /** @use HasFactory<GuestPhotoFactory> */
    use HasFactory;

    public const string DISK = 'memories';

    /**
     * @return BelongsTo<Wedding, $this>
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    /**
     * @return BelongsTo<Partner, $this>
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Where a wedding's photos live on the disk; removed as one with the wedding.
     */
    public static function directoryFor(int $weddingId): string
    {
        return "weddings/{$weddingId}";
    }

    protected static function booted(): void
    {
        static::deleting(function (GuestPhoto $photo): void {
            Storage::disk(self::DISK)->delete(array_filter([$photo->path, $photo->thumbnail_path]));
        });
    }
}
