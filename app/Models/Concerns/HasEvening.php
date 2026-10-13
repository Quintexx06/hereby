<?php

namespace App\Models\Concerns;

use App\Models\GroupPhoto;
use App\Models\ProgrammeItem;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * «Der Abend» on a wedding (spec 2026-10-13): what guests offer for the
 * evening, the group photos, and the witnesses' private link to run both.
 *
 * @property string|null $witness_token The witness link's credential.
 * @property int|null $programme_minutes The couple's time budget for the offers.
 */
trait HasEvening
{
    /**
     * @return HasMany<ProgrammeItem, $this>
     */
    public function programmeItems(): HasMany
    {
        return $this->hasMany(ProgrammeItem::class)->orderBy('position')->orderBy('id');
    }

    /**
     * @return HasMany<GroupPhoto, $this>
     */
    public function groupPhotos(): HasMany
    {
        return $this->hasMany(GroupPhoto::class)->orderBy('position')->orderBy('id');
    }

    /**
     * The witness link's token, made the first time the couple asks for it.
     */
    public function witnessToken(): string
    {
        if ($this->witness_token === null) {
            $this->renewWitnessToken();
        }

        return (string) $this->witness_token;
    }

    /**
     * A new witness link; the old one stops working at once.
     */
    public function renewWitnessToken(): void
    {
        $this->forceFill(['witness_token' => Str::random(40)])->save();
    }
}
