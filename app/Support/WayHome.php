<?php

namespace App\Support;

use App\Models\Wedding;
use Carbon\CarbonImmutable;

/**
 * The night's last connections (spec 2026-10-13), as moments in time:
 * a time before noon belongs to the night after the wedding day.
 */
class WayHome
{
    public const string TIME_ZONE = 'Europe/Zurich';

    /**
     * @return list<array{key: string, label: string, place: string|null, departs: CarbonImmutable}>
     */
    public static function connections(Wedding $wedding): array
    {
        if ($wedding->wedding_date === null) {
            return [];
        }

        $day = $wedding->wedding_date->toDateString();

        $connections = array_map(function (array $connection) use ($day): array {
            $departs = CarbonImmutable::parse("{$day} {$connection['time']}", self::TIME_ZONE);

            return [
                'key' => $connection['time'].'|'.$connection['label'],
                'label' => (string) $connection['label'],
                'place' => $connection['place'] ?? null,
                'departs' => $departs->hour < 12 ? $departs->addDay() : $departs,
            ];
        }, $wedding->way_home ?? []);

        usort($connections, fn (array $a, array $b): int => $a['departs'] <=> $b['departs']);

        return $connections;
    }
}
