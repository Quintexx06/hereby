<?php

namespace App\Guests\Import;

use Illuminate\Support\Str;

/**
 * A pasted list, one household per line:
 * "Familie Meier: Heidi, Peter, Lina (Kind)" or "Heidi und Peter Meier heidi@meier.ch".
 */
class PlainListParser
{
    public function __construct(private NameListParser $names) {}

    /**
     * @return list<ParsedHousehold>
     */
    public function parse(string $text): array
    {
        $households = [];

        foreach (preg_split('/\R/u', $text) ?: [] as $line) {
            $line = Str::squish($line);
            $email = null;

            if (preg_match('/<?([^\s<>@]+@[^\s<>@]+\.[^\s<>@]+)>?/u', $line, $match)) {
                $email = Str::lower($match[1]);
                $line = Str::squish(str_replace($match[0], ' ', $line));
            }

            [$name, $people] = str_contains($line, ':')
                ? array_map('trim', explode(':', $line, 2))
                : [null, $line];

            $guests = $this->names->parse($people);

            if ($guests === []) {
                continue;
            }

            $households[] = new ParsedHousehold($name ?: ParsedHousehold::nameFor($guests), $guests, $email);
        }

        return $households;
    }
}
