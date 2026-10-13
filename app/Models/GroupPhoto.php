<?php

namespace App\Models;

use Database\Factories\GroupPhotoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * One group photo on the couple's list (spec 2026-10-13): who is in it,
 * where, and when the witnesses called it on the day.
 *
 * @property int $id
 * @property int $wedding_id
 * @property string $title
 * @property string|null $place
 * @property int $position
 * @property Carbon|null $called_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['title', 'place', 'position'])]
class GroupPhoto extends Model
{
    /** @use HasFactory<GroupPhotoFactory> */
    use HasFactory;

    /** How long the call shows on the guests' personal links, in minutes. */
    public const int CALL_MINUTES = 20;

    /**
     * @return BelongsTo<Wedding, $this>
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    /**
     * @return BelongsToMany<Household, $this>
     */
    public function households(): BelongsToMany
    {
        return $this->belongsToMany(Household::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['called_at' => 'datetime', 'position' => 'integer'];
    }
}
