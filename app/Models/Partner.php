<?php

namespace App\Models;

use App\Enums\PartnerType;
use Database\Factories\PartnerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * A venue, photographer or planner. Sees a wedding only once its couple
 * connected it with the partner's code (spec 2026-10-15-partner-accounts).
 *
 * @property int $id
 * @property PartnerType $type
 * @property string $name
 * @property string $slug
 * @property string $code What couples type to connect the partner.
 * @property bool $is_showcase Venue Showcase: tour preview, public page, credit line.
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $website
 * @property string|null $address
 * @property string|null $postcode
 * @property string|null $town
 * @property int|null $capacity
 * @property string|null $about
 * @property string|null $travel
 * @property string|null $stay
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['type', 'name', 'email', 'phone', 'website', 'address', 'postcode', 'town', 'capacity', 'about', 'travel', 'stay'])]
class Partner extends Model
{
    /** @use HasFactory<PartnerFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Partner $partner): void {
            $partner->slug ??= Str::slug($partner->name).'-'.Str::lower(Str::random(4));
            $partner->code ??= self::newCode();
        });
    }

    /**
     * Four letters, a dash, four letters and digits, without look-alikes.
     */
    public static function newCode(): string
    {
        do {
            $pick = fn (int $length): string => collect(range(1, $length))
                ->map(fn (): string => 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'[random_int(0, 31)])
                ->implode('');
            $code = $pick(4).'-'.$pick(4);
        } while (self::query()->where('code', $code)->exists());

        return $code;
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * The weddings whose couples connected this partner.
     *
     * @return BelongsToMany<Wedding, $this>
     */
    public function weddings(): BelongsToMany
    {
        return $this->belongsToMany(Wedding::class)->withPivot('created_at');
    }

    /**
     * @return HasMany<VenueEnquiry, $this>
     */
    public function enquiries(): HasMany
    {
        return $this->hasMany(VenueEnquiry::class);
    }

    public function isVenue(): bool
    {
        return $this->type === PartnerType::Venue;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => PartnerType::class,
            'is_showcase' => 'boolean',
            'capacity' => 'integer',
        ];
    }
}
