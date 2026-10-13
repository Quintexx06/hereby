<?php

namespace App\Models;

use App\Enums\ProgrammeKind;
use App\Enums\ProgrammeStatus;
use Database\Factories\ProgrammeItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A speech, game or song a guest offers for the evening (spec 2026-10-13).
 * The witnesses decide; the couple sees only counts.
 *
 * @property int $id
 * @property int $wedding_id
 * @property int|null $household_id Who offered it, from their personal link.
 * @property string $contact_name
 * @property ProgrammeKind $kind
 * @property string $title
 * @property string|null $notes What they need: a microphone, a projector.
 * @property int $minutes
 * @property ProgrammeStatus $status
 * @property int $position Order in the evening, set by the witnesses.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['contact_name', 'kind', 'title', 'notes', 'minutes'])]
class ProgrammeItem extends Model
{
    /** @use HasFactory<ProgrammeItemFactory> */
    use HasFactory;

    /** @var array<string, mixed> */
    protected $attributes = ['status' => 'pending', 'position' => 0];

    /**
     * @return BelongsTo<Wedding, $this>
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    /**
     * @return BelongsTo<Household, $this>
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kind' => ProgrammeKind::class,
            'status' => ProgrammeStatus::class,
            'minutes' => 'integer',
            'position' => 'integer',
        ];
    }
}
