<?php

namespace App\Support;

use App\Enums\Celebration;
use App\Enums\EventType;

/**
 * Suggested parts of the day, with times, for each kind of celebration.
 * The single source for the programme step: the page shows these and the
 * couple adjusts them before saving.
 */
class ProgrammePresets
{
    /**
     * @return list<array{type: string, time: string, day_offset: int}>
     */
    public static function for(Celebration $celebration): array
    {
        $rows = match ($celebration) {
            Celebration::Day => [
                [EventType::Ceremony, '14:00', 0],
                [EventType::Reception, '15:30', 0],
                [EventType::Dinner, '18:30', 0],
            ],
            Celebration::Evening => [
                [EventType::Ceremony, '17:00', 0],
                [EventType::Dinner, '19:30', 0],
                [EventType::Party, '22:00', 0],
            ],
            Celebration::DayAndEvening => [
                [EventType::Ceremony, '14:00', 0],
                [EventType::Reception, '15:30', 0],
                [EventType::Dinner, '18:30', 0],
                [EventType::Party, '22:00', 0],
            ],
        };

        return array_map(fn (array $row): array => [
            'type' => $row[0]->value,
            'time' => $row[1],
            'day_offset' => $row[2],
        ], $rows);
    }

    /**
     * @return array<string, list<array{type: string, time: string, day_offset: int}>>
     */
    public static function all(): array
    {
        return collect(Celebration::cases())
            ->mapWithKeys(fn (Celebration $celebration): array => [$celebration->value => self::for($celebration)])
            ->all();
    }
}
