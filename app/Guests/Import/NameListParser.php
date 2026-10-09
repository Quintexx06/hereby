<?php

namespace App\Guests\Import;

use Illuminate\Support\Str;

/**
 * Reads the people out of one free-text entry, in any of our languages:
 * "Heidi und Peter Meier", "Camille et Marco Rossi", "Lina Meier (Kind)".
 */
class NameListParser
{
    private const string SEPARATORS = '/\s*(?:,|;|&|\+|\/|\bund\b|\band\b|\bet\b|\be\b)\s*/iu';

    private const string CHILD = '/\s*\((?:kind|child|enfant|bambino|bambina)\)\s*/iu';

    /**
     * @return list<ParsedGuest>
     */
    public function parse(string $text): array
    {
        $parts = array_values(array_filter(
            array_map(fn (string $part): string => Str::squish($part), preg_split(self::SEPARATORS, $text) ?: []),
            fn (string $part): bool => $part !== '',
        ));

        $guests = array_map(fn (string $part): ParsedGuest => $this->person($part), $parts);

        return $this->shareLastName($guests);
    }

    private function person(string $part): ParsedGuest
    {
        $isChild = (bool) preg_match(self::CHILD, $part);
        $words = explode(' ', Str::squish(preg_replace(self::CHILD, ' ', $part) ?? $part));

        $first = array_shift($words);
        $last = $words === [] ? null : implode(' ', $words);

        return new ParsedGuest(Str::ucfirst($first), $last, $isChild);
    }

    /**
     * "Heidi und Peter Meier": Heidi gets Peter's last name too.
     *
     * @param  list<ParsedGuest>  $guests
     * @return list<ParsedGuest>
     */
    private function shareLastName(array $guests): array
    {
        $last = end($guests);

        if (! $last || ! $last->lastName) {
            return $guests;
        }

        return array_map(fn (ParsedGuest $guest): ParsedGuest => $guest->lastName
            ? $guest
            : new ParsedGuest($guest->firstName, $last->lastName, $guest->isChild), $guests);
    }
}
