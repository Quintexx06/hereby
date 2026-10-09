<?php

namespace App\Guests\Import;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * The one entry point for every way a couple can bring their guest list:
 * pasted text (spreadsheet cells or a plain list) or an uploaded file
 * (.xlsx, .csv, .tsv, .txt, .vcf). Everything ends as ParsedHouseholds.
 */
class GuestListReader
{
    public const int MAX_ROWS = 1000;

    public function __construct(
        private PlainListParser $plain,
        private DelimitedListParser $delimited,
        private VCardParser $vcard,
        private XlsxReader $xlsx,
    ) {}

    /**
     * @return list<ParsedHousehold>
     */
    public function fromText(string $text): array
    {
        $text = $this->utf8($text);

        if (Str::contains($text, 'BEGIN:VCARD', ignoreCase: true)) {
            return $this->limit($this->vcard->parse($text));
        }

        $firstLine = Str::before(ltrim($text), "\n");

        // Cells copied from a spreadsheet arrive tab-separated; a CSV starts
        // with a header row we recognise. Anything else is a plain list.
        if (str_contains($firstLine, "\t") || $this->delimited->isHeader($firstLine)) {
            return $this->limit($this->delimited->parse(array_slice(DelimitedListParser::rows($text), 0, self::MAX_ROWS + 1)));
        }

        return $this->limit($this->plain->parse($text));
    }

    /**
     * @return list<ParsedHousehold>
     */
    public function fromFile(UploadedFile $file): array
    {
        return match (Str::lower($file->getClientOriginalExtension())) {
            'xlsx' => $this->limit($this->delimited->parse($this->xlsx->read($file->getRealPath(), self::MAX_ROWS + 1))),
            default => $this->fromText((string) file_get_contents($file->getRealPath())),
        };
    }

    /**
     * Excel on Windows still saves CSV as Windows-1252; strip the BOM too.
     */
    private function utf8(string $text): string
    {
        $text = preg_replace('/^\xEF\xBB\xBF/', '', $text) ?? $text;

        return mb_check_encoding($text, 'UTF-8') ? $text : mb_convert_encoding($text, 'UTF-8', 'Windows-1252');
    }

    /**
     * @param  list<ParsedHousehold>  $households
     * @return list<ParsedHousehold>
     */
    private function limit(array $households): array
    {
        return array_slice($households, 0, self::MAX_ROWS);
    }
}
