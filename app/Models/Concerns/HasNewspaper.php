<?php

namespace App\Models\Concerns;

use App\Models\NewspaperArticle;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * «Hochzeitszeitung» on a wedding (spec 2026-10-13): the stories friends
 * send through one unguessable link.
 *
 * @property string|null $newspaper_token The contribution link's credential.
 */
trait HasNewspaper
{
    /**
     * @return HasMany<NewspaperArticle, $this>
     */
    public function newspaperArticles(): HasMany
    {
        return $this->hasMany(NewspaperArticle::class)->orderBy('position')->orderBy('id');
    }

    /**
     * The contribution link's token, made the first time it is asked for.
     */
    public function newspaperToken(): string
    {
        if ($this->newspaper_token === null) {
            $this->forceFill(['newspaper_token' => Str::random(40)])->save();
        }

        return (string) $this->newspaper_token;
    }
}
