<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

/**
 * One distinct warning or error, for the team's «Protokoll». Recorded by
 * RecordLogEntry without personal data, kept 30 days after it last showed.
 *
 * @property int $id
 * @property string $level
 * @property string $fingerprint
 * @property string $message
 * @property string|null $exception
 * @property string|null $location
 * @property string|null $url
 * @property string|null $trace
 * @property int $count
 * @property CarbonImmutable $first_seen_at
 * @property CarbonImmutable $last_seen_at
 * @property CarbonImmutable|null $mailed_at
 * @property CarbonImmutable|null $resolved_at
 */
#[Fillable(['level', 'fingerprint', 'message', 'exception', 'location', 'url', 'trace', 'count', 'first_seen_at', 'last_seen_at'])]
class LogEntry extends Model
{
    use Prunable;

    public $timestamps = false;

    /** Levels that count as errors: shown in the badge and mailed. */
    public const ERROR_LEVELS = ['error', 'critical', 'alert', 'emergency'];

    /**
     * @param  Builder<static>  $query
     */
    public function scopeOpen(Builder $query): void
    {
        $query->whereNull('resolved_at');
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeErrors(Builder $query): void
    {
        $query->whereIn('level', self::ERROR_LEVELS);
    }

    public function isError(): bool
    {
        return in_array($this->level, self::ERROR_LEVELS, true);
    }

    /**
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        return static::query()->where('last_seen_at', '<', now()->subDays(30));
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'mailed_at' => 'datetime',
            'resolved_at' => 'datetime',
            'count' => 'integer',
        ];
    }
}
