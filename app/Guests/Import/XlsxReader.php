<?php

namespace App\Guests\Import;

use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

/**
 * Reads the first sheet of an .xlsx file into rows of strings, with no
 * spreadsheet library: an .xlsx file is a zip of XML parts.
 */
class XlsxReader
{
    /** Guards against zip bombs: no part may unpack beyond this size. */
    private const int MAX_PART_BYTES = 20_000_000;

    /**
     * @return list<list<string>>
     */
    public function read(string $path, int $maxRows): array
    {
        $zip = new ZipArchive;

        if ($zip->open($path) !== true) {
            throw new RuntimeException('Not an .xlsx file.');
        }

        try {
            $strings = $this->sharedStrings($this->part($zip, 'xl/sharedStrings.xml'));
            $sheet = $this->part($zip, 'xl/worksheets/sheet1.xml');
        } finally {
            $zip->close();
        }

        if ($sheet === null) {
            throw new RuntimeException('The file has no worksheet.');
        }

        $rows = [];

        foreach ($sheet->sheetData->row as $row) {
            if (count($rows) >= $maxRows) {
                break;
            }

            $cells = [];

            foreach ($row->c as $cell) {
                $column = $this->columnIndex((string) $cell['r']);
                $cells[$column] = match ((string) $cell['t']) {
                    's' => $strings[(int) $cell->v] ?? '',
                    'inlineStr' => (string) $cell->is->t,
                    default => (string) $cell->v,
                };
            }

            if ($cells !== []) {
                $rows[] = array_map(fn ($index) => trim($cells[$index] ?? ''), range(0, max(array_keys($cells))));
            }
        }

        return $rows;
    }

    private function part(ZipArchive $zip, string $name): ?SimpleXMLElement
    {
        $stat = $zip->statName($name);

        if ($stat === false) {
            return null;
        }

        if ($stat['size'] > self::MAX_PART_BYTES) {
            throw new RuntimeException('The file is too large.');
        }

        $xml = simplexml_load_string((string) $zip->getFromName($name), options: LIBXML_NONET);

        return $xml === false ? null : $xml;
    }

    /**
     * @return list<string>
     */
    private function sharedStrings(?SimpleXMLElement $xml): array
    {
        if ($xml === null) {
            return [];
        }

        $strings = [];

        foreach ($xml->si as $item) {
            // Plain text, or rich text split into runs.
            $strings[] = isset($item->t) ? (string) $item->t : implode('', array_map('strval', iterator_to_array($item->xpath('.//*[local-name()="t"]') ?: [], false)));
        }

        return $strings;
    }

    /**
     * "C12" → 2.
     */
    private function columnIndex(string $reference): int
    {
        $letters = preg_replace('/\d+/', '', $reference) ?: 'A';
        $index = 0;

        foreach (str_split($letters) as $letter) {
            $index = $index * 26 + (ord($letter) - 64);
        }

        return $index - 1;
    }
}
