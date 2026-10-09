<?php

namespace App\Models;

use App\Enums\Celebration;
use App\Enums\GuestEstimate;
use App\Enums\Locale;
use App\Enums\SetupStep;
use App\Enums\WeddingStatus;
use App\Enums\WeddingTheme;
use Database\Factories\WeddingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * One couple's wedding: the root of every guest-facing site.
 *
 * @property int $id
 * @property int $owner_id
 * @property string $slug
 * @property WeddingStatus $status
 * @property SetupStep|null $setup_step Furthest step reached in the setup.
 * @property Carbon|null $setup_completed_at
 * @property string $couple_names
 * @property string|null $partner_one
 * @property string|null $partner_two
 * @property Carbon|null $wedding_date Null only while drafting.
 * @property Carbon|null $rsvp_deadline
 * @property Locale $default_locale
 * @property Collection<int, Locale>|null $languages
 * @property WeddingTheme $theme
 * @property Celebration|null $celebration
 * @property GuestEstimate|null $guest_estimate
 * @property string|null $venue_name
 * @property string|null $venue_address
 * @property string|null $venue_postcode
 * @property string|null $venue_town
 * @property string|null $venue_lat
 * @property string|null $venue_lng
 * @property string|null $venue_reference Building address id in the federal register.
 * @property int|null $households_count Loaded with withCount().
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'slug', 'couple_names', 'partner_one', 'partner_two', 'wedding_date', 'rsvp_deadline',
    'default_locale', 'languages', 'theme', 'celebration', 'guest_estimate',
    'venue_name', 'venue_address', 'venue_postcode', 'venue_town', 'venue_lat', 'venue_lng', 'venue_reference',
])]
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

    public function isDraft(): bool
    {
        return $this->status === WeddingStatus::Draft;
    }

    /**
     * Weddings past the retention window, and drafts abandoned for as long.
     * Deleting them cascades to every household, guest and answer at the
     * database level.
     *
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        $cutoff = now()->subMonths(config()->integer('hereby.retention_months'))->startOfDay();

        return static::query()
            ->where('wedding_date', '<', $cutoff)
            ->orWhere(fn (Builder $query) => $query
                ->where('status', WeddingStatus::Draft)
                ->where('updated_at', '<', $cutoff));
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
            'languages' => AsEnumCollection::of(Locale::class),
            'theme' => WeddingTheme::class,
            'status' => WeddingStatus::class,
            'setup_step' => SetupStep::class,
            'setup_completed_at' => 'datetime',
            'celebration' => Celebration::class,
            'guest_estimate' => GuestEstimate::class,
        ];
    }
}
