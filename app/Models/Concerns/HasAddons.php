<?php

namespace App\Models\Concerns;

use App\Enums\Addon;
use App\Enums\Plan;
use App\Models\WeddingAddon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * What a wedding may use beyond its plan: «Der Abend» with Atelier and up,
 * and the extras bought one by one (spec 2026-10-13).
 *
 * @property Carbon|null $memories_kept_until Photos and newspaper stay until then (ADR 0011).
 * @property Carbon|null $guest_data_deleted_at When guest data went while the memories stayed.
 * @property string|null $custom_domain The couple's own address, e.g. anna-und-luca.ch.
 * @property Carbon|null $custom_domain_verified_at When the team connected it.
 * @property Carbon|null $seating_published_at When guests started seeing their table mates.
 * @property list<array{label: string, place: string|null, time: string}>|null $way_home The night's last connections.
 * @property list<string>|null $way_home_sent Connections whose reminder went out.
 */
trait HasAddons
{
    /**
     * @return HasMany<WeddingAddon, $this>
     */
    public function addons(): HasMany
    {
        return $this->hasMany(WeddingAddon::class);
    }

    /**
     * Table mates, way home, group photos and programme sign-ups.
     */
    public function includesEvening(): bool
    {
        return $this->plan !== null && $this->plan->rank() >= Plan::Atelier->rank();
    }

    public function hasAddon(Addon $addon): bool
    {
        if ($addon === Addon::KeepMemories) {
            return $this->memories_kept_until !== null && ! $this->memories_kept_until->isPast();
        }

        return $addon->includedIn($this->plan) || $this->addons->contains('addon', $addon);
    }

    /**
     * Only with a paid plan that allows it, and once unless bought per year.
     */
    public function canBuy(Addon $addon): bool
    {
        if ($this->plan === null || $this->plan->rank() < $addon->requires()->rank()) {
            return false;
        }

        return $addon->isRepeatable() || ! $this->hasAddon($addon);
    }

    /**
     * The couple's own domain once the team has connected it; null before.
     */
    public function connectedDomain(): ?string
    {
        return $this->custom_domain_verified_at !== null ? $this->custom_domain : null;
    }

    /**
     * One more year for the memories, counted from when they would go.
     */
    public function keepMemoriesAnotherYear(): void
    {
        $retentionEnd = $this->wedding_date?->copy()->addMonths(config()->integer('hereby.retention_months'));
        $from = collect([$retentionEnd, $this->memories_kept_until, now()->startOfDay()])->filter()->max();

        $this->forceFill(['memories_kept_until' => $from->copy()->addYear()])->save();
    }
}
