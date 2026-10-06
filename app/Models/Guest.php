<?php

namespace App\Models;

use Database\Factories\GuestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * One person in a household.
 *
 * @property int $id
 * @property int $household_id
 * @property string $first_name
 * @property string|null $last_name
 * @property bool $is_child
 * @property bool $is_plus_one
 * @property string|null $dietary_notes Sensitive health data; encrypted at rest.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['first_name', 'last_name', 'is_child', 'is_plus_one', 'dietary_notes'])]
#[Hidden(['dietary_notes'])]
class Guest extends Model
{
    /** @use HasFactory<GuestFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Household, $this>
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * @return HasMany<EventResponse, $this>
     */
    public function responses(): HasMany
    {
        return $this->hasMany(EventResponse::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_child' => 'boolean',
            'is_plus_one' => 'boolean',
            'dietary_notes' => 'encrypted',
        ];
    }
}
