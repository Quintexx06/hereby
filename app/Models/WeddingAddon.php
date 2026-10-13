<?php

namespace App\Models;

use App\Enums\Addon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * One paid extra on a wedding (spec 2026-10-13).
 *
 * @property int $id
 * @property int $wedding_id
 * @property Addon $addon
 * @property string|null $stripe_session
 * @property string|null $stripe_payment_intent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class WeddingAddon extends Model
{
    /**
     * @return BelongsTo<Wedding, $this>
     */
    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['addon' => Addon::class];
    }
}
