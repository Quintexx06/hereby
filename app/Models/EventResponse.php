<?php

namespace App\Models;

use App\Enums\ResponseStatus;
use Database\Factories\EventResponseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * One guest's answer for one event.
 *
 * @property int $id
 * @property int $guest_id
 * @property int $event_id
 * @property ResponseStatus $status
 * @property string|null $menu_choice
 * @property Carbon|null $responded_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['event_id', 'status', 'menu_choice', 'responded_at'])]
class EventResponse extends Model
{
    /** @use HasFactory<EventResponseFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Guest, $this>
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ResponseStatus::class,
            'responded_at' => 'datetime',
        ];
    }
}
