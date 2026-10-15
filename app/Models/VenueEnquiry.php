<?php

namespace App\Models;

use Database\Factories\VenueEnquiryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Someone asks a Showcase venue for a tour from its public page. Kept for
 * the venue for 12 months, then deleted (ADR 0007).
 *
 * @property int $id
 * @property int $partner_id
 * @property string $name
 * @property string $email
 * @property Carbon|null $preferred_date
 * @property string|null $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'preferred_date', 'message'])]
class VenueEnquiry extends Model
{
    /** @use HasFactory<VenueEnquiryFactory> */
    use HasFactory, Prunable;

    /**
     * @return BelongsTo<Partner, $this>
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        return static::query()->where('created_at', '<', now()->subMonths(12));
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['preferred_date' => 'date'];
    }
}
