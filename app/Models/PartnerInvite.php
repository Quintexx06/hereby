<?php

namespace App\Models;

use App\Enums\PartnerType;
use Database\Factories\PartnerInviteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * A couple's invitation to a partner not yet on Hereby. The link opens an
 * account that is already connected to the wedding; it is deleted once
 * used, and unused ones after 30 days (ADR 0007).
 *
 * @property int $id
 * @property int $wedding_id
 * @property PartnerType $type
 * @property string $name
 * @property string $email
 * @property string $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Wedding $wedding
 */
#[Fillable(['type', 'name', 'email'])]
class PartnerInvite extends Model
{
    /** @use HasFactory<PartnerInviteFactory> */
    use HasFactory, Prunable;

    public const DAYS_VALID = 30;

    protected static function booted(): void
    {
        static::creating(function (PartnerInvite $invite): void {
            $invite->token ??= Str::random(48);
        });
    }

    /**
     * @return BelongsTo<Wedding, $this>
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    /**
     * Invites whose link still works.
     *
     * @param  Builder<static>  $query
     */
    public function scopeValid(Builder $query): void
    {
        $query->where('created_at', '>=', now()->subDays(self::DAYS_VALID));
    }

    /**
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        return static::query()->where('created_at', '<', now()->subDays(self::DAYS_VALID));
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['type' => PartnerType::class];
    }
}
