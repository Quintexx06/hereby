<?php

namespace App\Guests\Import;

use Illuminate\Support\Str;

/**
 * A contacts export (.vcf) from an iPhone, Android or Outlook address book.
 * Each contact becomes its own household; merge them in the preview.
 */
class VCardParser
{
    /**
     * @return list<ParsedHousehold>
     */
    public function parse(string $text): array
    {
        // Unfold continuation lines (RFC 6350: a line starting with a space or tab).
        $text = preg_replace('/\R[ \t]/u', '', $text) ?? $text;
        preg_match_all('/BEGIN:VCARD(.*?)END:VCARD/si', $text, $cards);

        $households = [];

        foreach ($cards[1] as $card) {
            $fields = $this->fields($card);
            [$last, $first] = array_pad(explode(';', $fields['N'] ?? ''), 2, '');

            if (trim($first) === '' && isset($fields['FN'])) {
                [$first, $last] = array_pad(explode(' ', $fields['FN'], 2), 2, '');
            }

            if (trim($first) === '') {
                continue;
            }

            $guest = new ParsedGuest(Str::squish($first), Str::squish($last) ?: null);
            $email = $fields['EMAIL'] ?? null;

            $households[] = new ParsedHousehold(
                $guest->fullName(),
                [$guest],
                filter_var($email, FILTER_VALIDATE_EMAIL) ? Str::lower($email) : null,
            );
        }

        return $households;
    }

    /**
     * The first value of each property, decoded. "EMAIL;TYPE=HOME:a@b.ch" → EMAIL.
     *
     * @return array<string, string>
     */
    private function fields(string $card): array
    {
        $fields = [];

        foreach (preg_split('/\R/u', $card) ?: [] as $line) {
            if (! str_contains($line, ':')) {
                continue;
            }

            [$property, $value] = explode(':', $line, 2);
            $parameters = explode(';', $property);
            $name = Str::upper(Str::afterLast($parameters[0], '.'));

            if (Str::contains(Str::upper($property), 'QUOTED-PRINTABLE')) {
                $value = quoted_printable_decode($value);
            }

            $fields[$name] ??= trim($value);
        }

        return $fields;
    }
}
