<?php

namespace App\Models;

use App\Enums\EventType;
use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * A part of the wedding a household can be invited to (civil ceremony, dinner…).
 *
 * @property int $id
 * @property int $wedding_id
 * @property EventType $type
 * @property string|null $name
 * @property Carbon $starts_at
 * @property Carbon|null $ends_at
 * @property string|null $location_name
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['type', 'name', 'starts_at', 'ends_at', 'location_name', 'address'])]
class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;

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
            'type' => EventType::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }
}
