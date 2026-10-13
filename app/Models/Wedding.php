<?php

namespace App\Models;

use App\Enums\Celebration;
use App\Enums\GuestEstimate;
use App\Enums\Locale;
use App\Enums\LookStyle;
use App\Enums\Plan;
use App\Enums\SetupStep;
use App\Enums\WeddingStatus;
use App\Enums\WeddingTemplate;
use App\Enums\WeddingTheme;
use App\Models\Concerns\HasAddons;
use App\Models\Concerns\HasEvening;
use App\Models\Concerns\HasMemories;
use App\Models\Concerns\HasNewspaper;
use Database\Factories\WeddingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
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
 * @property Plan|null $plan What the couple paid for; null until they send.
 * @property Carbon|null $paid_at When the personal links went live.
 * @property string|null $stripe_payment_intent The latest Stripe payment, for refunds.
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
 * @property WeddingTemplate $template Layout and motion of the guest site.
 * @property Collection<int, LookStyle>|null $look_styles Style directions for the team (up to three).
 * @property string|null $look_wishes The couple's own idea, in their words.
 * @property Celebration|null $celebration
 * @property GuestEstimate|null $guest_estimate
 * @property string|null $venue_name
 * @property string|null $venue_address
 * @property string|null $venue_postcode
 * @property string|null $venue_town
 * @property string|null $venue_lat
 * @property string|null $venue_lng
 * @property string|null $venue_reference Building address id in the federal register.
 * @property list<array{key: string, label: string}>|null $menu_options The couple's menus, asked at the dinner.
 * @property bool $children_menu
 * @property bool $offers_shuttle
 * @property bool $offers_stay
 * @property bool $asks_song
 * @property bool $sends_reminders
 * @property int|null $households_count Loaded with withCount().
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'slug', 'couple_names', 'partner_one', 'partner_two', 'wedding_date', 'rsvp_deadline',
    'default_locale', 'languages', 'theme', 'template', 'look_styles', 'look_wishes', 'celebration', 'guest_estimate',
    'venue_name', 'venue_address', 'venue_postcode', 'venue_town', 'venue_lat', 'venue_lng', 'venue_reference',
    'menu_options', 'children_menu', 'offers_shuttle', 'offers_stay', 'asks_song', 'sends_reminders',
])]
class Wedding extends Model
{
    /** @use HasFactory<WeddingFactory> */
    use HasAddons, HasEvening, HasFactory, HasMemories, HasNewspaper, Prunable;

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
     * @return HasMany<ContentBlock, $this>
     */
    public function contentBlocks(): HasMany
    {
        return $this->hasMany(ContentBlock::class)->orderBy('position')->orderBy('id');
    }

    public function isDraft(): bool
    {
        return $this->status === WeddingStatus::Draft;
    }

    /**
     * Personal links open for guests only once a plan is paid.
     */
    public function isPublished(): bool
    {
        return $this->paid_at !== null;
    }

    /**
     * What this plan costs now, in Rappen: the full price, or the difference
     * to the plan already paid. Zero when it is not an upgrade.
     */
    public function amountFor(Plan $plan): int
    {
        if ($this->plan !== null && $this->plan->rank() >= $plan->rank()) {
            return 0;
        }

        return $plan->price() - ($this->plan?->price() ?? 0);
    }

    /**
     * Guests may answer, and change their answer, until the end of the deadline day.
     */
    public function acceptsReplies(): bool
    {
        return $this->rsvp_deadline === null || ! $this->rsvp_deadline->copy()->endOfDay()->isPast();
    }

    /**
     * Menu keys a guest may choose; `children` only for children, if offered.
     *
     * @return list<string>
     */
    public function menuKeys(bool $forChild = false): array
    {
        $keys = array_column($this->menu_options ?? [], 'key');

        return $forChild && $this->children_menu ? [...$keys, 'children'] : $keys;
    }

    public function asksForMenu(): bool
    {
        return $this->menuKeys() !== [] || $this->children_menu;
    }

    /**
     * Weddings past the retention window, and drafts abandoned for as long.
     * Deleting them cascades to every household, guest and answer at the
     * database level; one by one, so each wedding's photos go with it.
     * Kept memories (ADR 0011) wait until their year is over; their guest
     * data is deleted on time by StripGuestData.
     *
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        $cutoff = now()->subMonths(config()->integer('hereby.retention_months'))->startOfDay();

        return static::query()
            ->where(fn (Builder $query) => $query
                ->where('wedding_date', '<', $cutoff)
                ->where(fn (Builder $kept) => $kept
                    ->whereNull('memories_kept_until')
                    ->orWhere('memories_kept_until', '<', now()->toDateString())))
            ->orWhere(fn (Builder $query) => $query
                ->where('status', WeddingStatus::Draft)
                ->where('updated_at', '<', $cutoff));
    }

    /** @var array<string, mixed> */
    protected $attributes = ['template' => 'atelier'];

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
            'template' => WeddingTemplate::class,
            'look_styles' => AsEnumCollection::of(LookStyle::class),
            'status' => WeddingStatus::class,
            'plan' => Plan::class,
            'paid_at' => 'datetime',
            'memories_kept_until' => 'date',
            'seating_published_at' => 'datetime',
            'guest_data_deleted_at' => 'datetime',
            'way_home' => 'array',
            'way_home_sent' => 'array',
            'setup_step' => SetupStep::class,
            'setup_completed_at' => 'datetime',
            'celebration' => Celebration::class,
            'guest_estimate' => GuestEstimate::class,
            'menu_options' => 'array',
            'children_menu' => 'boolean',
            'offers_shuttle' => 'boolean',
            'offers_stay' => 'boolean',
            'asks_song' => 'boolean',
            'sends_reminders' => 'boolean',
        ];
    }
}
