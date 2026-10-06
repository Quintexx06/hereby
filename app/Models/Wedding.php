<?php

namespace App\Models;

use App\Enums\Locale;
use App\Enums\WeddingTheme;
use Database\Factories\WeddingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * One couple's wedding: the root of every guest-facing site.
 *
 * @property int $id
 * @property int $owner_id
 * @property string $slug
 * @property string $couple_names
 * @property Carbon $wedding_date
 * @property Carbon|null $rsvp_deadline
 * @property Locale $default_locale
 * @property WeddingTheme $theme
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['slug', 'couple_names', 'wedding_date', 'rsvp_deadline', 'default_locale', 'theme'])]
class Wedding extends Model
{
    /** @use HasFactory<WeddingFactory> */
    use HasFactory, MassPrunable;

    /**
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return HasMany<Event, $this>
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('starts_at');
    }

    /**
     * @return HasMany<Household, $this>
     */
    public function households(): HasMany
    {
        return $this->hasMany(Household::class);
    }

    /**
     * Weddings past the retention window. Deleting them cascades to every
     * household, guest and answer at the database level.
     *
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        return static::query()->where(
            'wedding_date',
            '<',
            now()->subMonths(config()->integer('hereby.retention_months'))->startOfDay(),
        );
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'wedding_date' => 'date',
            'rsvp_deadline' => 'date',
            'default_locale' => Locale::class,
            'theme' => WeddingTheme::class,
        ];
    }
}
