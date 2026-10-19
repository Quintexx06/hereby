<?php

namespace App\Actions\Partners;

use App\Enums\PartnerType;
use App\Models\Partner;
use Illuminate\Support\Str;

/**
 * Adds prospect venues from a pasted list, one per line:
 * «Name; Ort; E-Mail; Website; persönlicher Satz» (tabs work too, only the
 * name is required). A venue that is already in the list is left as it is,
 * so pasting the same list twice adds nobody twice.
 */
class ImportProspects
{
    /**
     * @return array{added: int, skipped: int, invalid: list<int>}
     */
    public function handle(string $lines): array
    {
        $result = ['added' => 0, 'skipped' => 0, 'invalid' => []];

        foreach (preg_split('/\R/', $lines) ?: [] as $number => $line) {
            if (trim($line) === '') {
                continue;
            }

            $prospect = $this->parse($line);

            if ($prospect === null) {
                $result['invalid'][] = $number + 1;

                continue;
            }

            if (Partner::query()->where('type', PartnerType::Venue)->whereRaw('lower(name) = ?', [Str::lower($prospect['name'])])->exists()) {
                $result['skipped']++;

                continue;
            }

            Partner::query()->create(['type' => PartnerType::Venue, ...$prospect]);
            $result['added']++;
        }

        return $result;
    }

    /**
     * @return array{name: string, town: string|null, email: string|null, website: string|null, pitch_note: string|null}|null
     */
    private function parse(string $line): ?array
    {
        $cells = array_map(fn (string $cell): string => trim($cell), preg_split('/\t|;/', $line) ?: []);
        [$name, $town, $email, $website, $note] = array_pad($cells, 5, '');
        $email = Str::lower($email);

        if ($name === '' || Str::length($name) > 120
            || ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) === false)
            || ($website !== '' && ! Str::isUrl($website, ['http', 'https']))) {
            return null;
        }

        return [
            'name' => $name,
            'town' => Str::limit($town, 80, '') ?: null,
            'email' => $email ?: null,
            'website' => $website ?: null,
            'pitch_note' => Str::limit($note, 500, '') ?: null,
        ];
    }
}
