<?php

namespace App\Guests\Import;

use Illuminate\Support\Str;

/**
 * Spreadsheet rows (pasted from Excel or Sheets, CSV, or an .xlsx sheet).
 * Columns are recognised by their header in DE, FR, IT or EN; people who share
 * a "Haushalt" value become one household.
 */
class DelimitedListParser
{
    /** @var array<string, list<string>> */
    private const array HEADERS = [
        'household' => ['haushalt', 'familie', 'household', 'gruppe', 'einladung', 'famille', 'famiglia', 'party'],
        'name' => ['name', 'gast', 'guest', 'gaste', 'namen', 'nom complet', 'full name'],
        'first' => ['vorname', 'first name', 'firstname', 'prenom', 'nome', 'given name'],
        'last' => ['nachname', 'last name', 'lastname', 'surname', 'familienname', 'nom', 'cognome', 'family name'],
        'email' => ['e-mail', 'email', 'mail', 'e-mail-adresse', 'courriel'],
        'language' => ['sprache', 'language', 'langue', 'lingua'],
        'child' => ['kind', 'child', 'kinder', 'enfant', 'bambino', 'children'],
    ];

    public function __construct(private NameListParser $names) {}

    /**
     * Splits pasted or CSV text into rows, guessing the delimiter.
     *
     * @return list<list<string>>
     */
    public static function rows(string $text): array
    {
        $lines = array_values(array_filter(preg_split('/\R/u', $text) ?: [], fn ($line) => trim($line) !== ''));
        $first = $lines[0] ?? '';
        $delimiter = collect(["\t", ';', ','])->sortByDesc(fn ($d) => substr_count($first, $d))->first();

        return array_map(
            fn (string $line): array => array_map(fn (?string $cell): string => trim((string) $cell), str_getcsv($line, $delimiter, '"', '')),
            $lines,
        );
    }

    /**
     * @param  list<list<string>>  $rows
     * @return list<ParsedHousehold>
     */
    public function parse(array $rows): array
    {
        $columns = $this->columns($rows[0] ?? []);

        if ($columns !== []) {
            array_shift($rows);
        } else {
            // No header: one name column, or first and last name.
            $columns = count($rows[0] ?? []) === 2 ? ['first' => 0, 'last' => 1] : ['name' => 0];
        }

        $groups = [];

        foreach ($rows as $index => $row) {
            $cell = fn (string $key): string => isset($columns[$key]) ? trim($row[$columns[$key]] ?? '') : '';
            $guests = $this->guests($cell);

            if ($guests === []) {
                continue;
            }

            $key = $cell('household') !== '' ? 'h:'.Str::lower($cell('household')) : 'row:'.$index;
            $groups[$key] ??= ['name' => $cell('household'), 'guests' => [], 'email' => null, 'language' => null];
            array_push($groups[$key]['guests'], ...$guests);
            $groups[$key]['email'] ??= $cell('email') !== '' ? Str::lower($cell('email')) : null;
            $groups[$key]['language'] ??= $cell('language') !== '' ? $cell('language') : null;
        }

        return array_values(array_map(fn (array $group): ParsedHousehold => new ParsedHousehold(
            $group['name'] ?: ParsedHousehold::nameFor($group['guests']),
            $group['guests'],
            filter_var($group['email'], FILTER_VALIDATE_EMAIL) ? $group['email'] : null,
            LocaleGuesser::guess($group['language']),
        ), $groups));
    }

    /**
     * @param  callable(string): string  $cell
     * @return list<ParsedGuest>
     */
    private function guests(callable $cell): array
    {
        $isChild = in_array(Str::lower($cell('child')), ['ja', 'yes', 'x', '1', 'oui', 'si', 'sì', 'true'], true);

        if ($cell('first') !== '') {
            return [new ParsedGuest($cell('first'), $cell('last') ?: null, $isChild)];
        }

        $guests = $this->names->parse($cell('name') !== '' ? $cell('name') : $cell('household'));

        return $isChild && count($guests) === 1 ? [new ParsedGuest($guests[0]->firstName, $guests[0]->lastName, true)] : $guests;
    }

    /**
     * Whether a line is a header row we recognise ("Vorname;Nachname", "Name,E-Mail").
     */
    public function isHeader(string $line): bool
    {
        return $this->columns(self::rows($line)[0] ?? []) !== [];
    }

    /**
     * @param  list<string>  $header
     * @return array<string, int>
     */
    private function columns(array $header): array
    {
        $columns = [];

        foreach ($header as $index => $title) {
            $title = Str::lower(Str::ascii(trim($title)));

            foreach (self::HEADERS as $key => $aliases) {
                if (! isset($columns[$key]) && in_array($title, $aliases, true)) {
                    $columns[$key] = $index;
                }
            }
        }

        return isset($columns['name']) || isset($columns['first']) || isset($columns['household']) ? $columns : [];
    }
}
