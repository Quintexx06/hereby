<?php

namespace App\Guests\Import;

use App\Enums\Locale;

/**
 * One household read from an imported guest list, before it is saved.
 */
final readonly class ParsedHousehold
{
    /**
     * @param  list<ParsedGuest>  $guests
     */
    public function __construct(
        public string $name,
        public array $guests,
        public ?string $email = null,
        public ?Locale $locale = null,
    ) {}

    /**
     * A household name from its guests when the list didn't give one:
     * "Familie Meier", "Heidi Meier" or "Heidi & Marco".
     *
     * @param  list<ParsedGuest>  $guests
     */
    public static function nameFor(array $guests): string
    {
        $lastNames = array_values(array_unique(array_filter(array_map(fn (ParsedGuest $guest) => $guest->lastName, $guests))));

        return match (true) {
            count($guests) === 1 => $guests[0]->fullName(),
            count($lastNames) === 1 && count($guests) > 2 => 'Familie '.$lastNames[0],
            count($lastNames) === 1 => implode(' & ', array_map(fn (ParsedGuest $guest) => $guest->firstName, $guests)).' '.$lastNames[0],
            default => implode(' & ', array_map(fn (ParsedGuest $guest) => $guest->firstName, $guests)),
        };
    }

    /**
     * @return array{name: string, email: string|null, locale: string|null, guests: list<array{first_name: string, last_name: string|null, is_child: bool}>}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'locale' => $this->locale?->value,
            'guests' => array_map(fn (ParsedGuest $guest) => $guest->toArray(), $this->guests),
        ];
    }
}
