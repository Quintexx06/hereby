<?php

namespace App\Models\Concerns;

use App\Models\GuestPhoto;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * «Erinnerungen» on a wedding (Atelier and up): the photos guests send
 * through one unguessable upload link, and their files on the server.
 *
 * @property string|null $memories_token The upload link's credential.
 */
trait HasMemories
{
    /**
     * @return HasMany<GuestPhoto, $this>
     */
    public function photos(): HasMany
    {
        return $this->hasMany(GuestPhoto::class);
    }

    public function includesMemories(): bool
    {
        return $this->plan?->includesMemories() === true;
    }

    /**
     * The upload link's token, made the first time the couple asks for it.
     */
    public function memoriesToken(): string
    {
        if ($this->memories_token === null) {
            $this->forceFill(['memories_token' => Str::random(40)])->save();
        }

        return (string) $this->memories_token;
    }

    /**
     * Guest photos leave the server with the wedding (ADR 0007), however it
     * is deleted: by the couple, with their account, or by retention.
     */
    protected static function bootHasMemories(): void
    {
        static::deleted(function (self $wedding): void {
            Storage::disk(GuestPhoto::DISK)->deleteDirectory(GuestPhoto::directoryFor($wedding->id));
        });
    }
}
