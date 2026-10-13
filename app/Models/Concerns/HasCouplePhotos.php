<?php

namespace App\Models\Concerns;

use App\Models\WeddingPhoto;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The couple's own photos on a wedding, cover first. Their files sit in
 * the wedding's directory, which HasMemories removes with the wedding.
 */
trait HasCouplePhotos
{
    /**
     * @return HasMany<WeddingPhoto, $this>
     */
    public function couplePhotos(): HasMany
    {
        return $this->hasMany(WeddingPhoto::class)->orderBy('position')->orderBy('id');
    }
}
