<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * An inspiration photo the couple adds to their brief. Private: only the
 * couple and the team open it, and it leaves with the wedding.
 *
 * @property int $id
 * @property int $design_brief_id
 * @property string $path A web copy on the `memories` disk.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class BriefPhoto extends Model
{
    public const string DISK = GuestPhoto::DISK;

    /**
     * @return BelongsTo<DesignBrief, $this>
     */
    public function brief(): BelongsTo
    {
        return $this->belongsTo(DesignBrief::class, 'design_brief_id');
    }

    public static function directoryFor(int $weddingId): string
    {
        return GuestPhoto::directoryFor($weddingId).'/brief';
    }

    protected static function booted(): void
    {
        static::deleting(function (BriefPhoto $photo): void {
            Storage::disk(self::DISK)->delete($photo->path);
        });
    }
}
