<?php

namespace App\Models;

use App\Enums\BriefStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * A couple's brief for «Massanfertigung»: what the team should build.
 *
 * @property int $id
 * @property int $wedding_id
 * @property BriefStatus $status
 * @property string|null $wishes
 * @property list<string>|null $colors Up to three #rrggbb.
 * @property string|null $mood
 * @property string|null $links
 * @property Carbon|null $ready_by
 * @property string|null $team_note
 * @property Carbon|null $submitted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['wishes', 'colors', 'mood', 'links', 'ready_by'])]
class DesignBrief extends Model
{
    public const int MAX_PHOTOS = 12;

    /** @var array<string, string> */
    protected $attributes = ['status' => 'draft'];

    /**
     * @return BelongsTo<Wedding, $this>
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    /**
     * @return HasMany<BriefPhoto, $this>
     */
    public function photos(): HasMany
    {
        return $this->hasMany(BriefPhoto::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => BriefStatus::class,
            'colors' => 'array',
            'ready_by' => 'date',
            'submitted_at' => 'datetime',
        ];
    }
}
