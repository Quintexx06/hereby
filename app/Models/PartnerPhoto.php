<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * A venue's own photo (a web copy, longest edge 1600px), for its public
 * page and the tour preview. Public by design: the token is the link.
 *
 * @property int $id
 * @property int $partner_id
 * @property string $token
 * @property string $path
 * @property int $width
 * @property int $height
 * @property int $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PartnerPhoto extends Model
{
    public const string DISK = WeddingPhoto::DISK;

    public const int MAX = 6;

    /** @var list<string> */
    protected $fillable = ['position'];

    /**
     * @return BelongsTo<Partner, $this>
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function url(): string
    {
        return route('partner-photos.show', $this->token);
    }

    public static function directoryFor(int $partnerId): string
    {
        return "partners/{$partnerId}";
    }

    protected static function booted(): void
    {
        static::creating(function (PartnerPhoto $photo): void {
            $photo->token ??= Str::random(40);
        });

        static::deleting(function (PartnerPhoto $photo): void {
            Storage::disk(self::DISK)->delete($photo->path);
        });
    }
}
