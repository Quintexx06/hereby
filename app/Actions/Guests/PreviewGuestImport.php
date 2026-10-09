<?php

namespace App\Actions\Guests;

use App\Guests\Import\ParsedHousehold;
use App\Models\Guest;
use App\Models\Wedding;
use Illuminate\Support\Str;

/**
 * Turns parsed households into the preview the couple confirms: every guest
 * is flagged when the same name is already on the list (or twice in the file).
 */
class PreviewGuestImport
{
    /**
     * @param  list<ParsedHousehold>  $households
     * @return list<array{name: string, email: string|null, locale: string|null, guests: list<array{first_name: string, last_name: string|null, is_child: bool, duplicate: bool}>}>
     */
    public function handle(Wedding $wedding, array $households): array
    {
        $known = Guest::query()
            ->whereHas('household', fn ($query) => $query->whereBelongsTo($wedding))
            ->get(['first_name', 'last_name'])
            ->mapWithKeys(fn (Guest $guest): array => [self::key($guest->first_name, $guest->last_name) => true])
            ->all();

        return array_map(function (ParsedHousehold $household) use (&$known): array {
            $row = $household->toArray();

            foreach ($row['guests'] as $index => $guest) {
                $key = self::key($guest['first_name'], $guest['last_name']);
                $row['guests'][$index]['duplicate'] = isset($known[$key]);
                $known[$key] = true;
            }

            return $row;
        }, $households);
    }

    /**
     * "Zoë  Müller" and "zoe muller" are the same person.
     */
    public static function key(string $firstName, ?string $lastName): string
    {
        return Str::lower(Str::ascii(Str::squish($firstName.' '.$lastName)));
    }
}
