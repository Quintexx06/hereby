<?php

namespace App\Models;

use App\Enums\Locale;
use App\Enums\ReplyStatus;
use Database\Factories\HouseholdFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\RouteKey;
use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * The people who share one invitation and one personal link.
 *
 * @property int $id
 * @property int $wedding_id
 * @property string $name
 * @property string|null $email
 * @property Locale $locale
 * @property string $token
 * @property bool $plus_one_allowed
 * @property Carbon|null $opened_at
 * @property int|null $shuttle_seats
 * @property bool|null $needs_stay
 * @property string|null $song_wish
 * @property Carbon|null $responded_at
 * @property bool|null $has_answered Loaded with withExists() (BuildWeddingOverview).
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'locale', 'plus_one_allowed'])]
#[Hidden(['token'])]
#[RouteKey('token')]
class Household extends Model
{
    /** @use HasFactory<HouseholdFactory> */
    use HasFactory, HasUniqueStringIds;

    /**
     * Length of the random personal-link token (see ADR 0005).
     */
    public const int TOKEN_LENGTH = 40;

    /**
     * Columns filled with a fresh unique value on insert. Unlike a `creating`
     * listener, this also runs when model events are muted (seeders, quiet saves).
     *
     * @return list<string>
     */
    public function uniqueIds(): array
    {
        return ['token'];
    }

    public function newUniqueId(): string
    {
        return Str::random(self::TOKEN_LENGTH);
    }

    /**
     * Malformed tokens 404 before touching the database.
     */
    protected function isValidUniqueId($value): bool
    {
        return is_string($value) && preg_match('/\A[A-Za-z0-9]{'.self::TOKEN_LENGTH.'}\z/', $value) === 1;
    }

    /**
     * @return BelongsTo<Wedding, $this>
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    /**
     * The events this household is invited to.
     *
     * @return BelongsToMany<Event, $this>
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)->orderBy('starts_at');
    }

    /**
     * @return HasMany<Guest, $this>
     */
    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    /**
     * Where the household stands; needs `has_answered` from withExists().
     */
    public function replyStatus(): ReplyStatus
    {
        return ReplyStatus::for($this->opened_at !== null, (bool) $this->has_answered);
    }

    /**
     * Record the first time the household opened its link.
     */
    public function markOpened(): void
    {
        if ($this->opened_at === null) {
            $this->forceFill(['opened_at' => now()])->save();
        }
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'locale' => Locale::class,
            'plus_one_allowed' => 'boolean',
            'opened_at' => 'datetime',
            'needs_stay' => 'boolean',
            'responded_at' => 'datetime',
        ];
    }
}
