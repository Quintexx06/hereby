<?php

namespace Tests\Unit\Guests;

use App\Enums\Locale;
use App\Guests\Import\GuestListReader;
use App\Guests\Import\ParsedHousehold;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use ZipArchive;

class GuestListReaderTest extends TestCase
{
    private function reader(): GuestListReader
    {
        return $this->app->make(GuestListReader::class);
    }

    /**
     * @param  list<ParsedHousehold>  $households
     * @return list<array{0: string, 1: list<string>}>
     */
    private function summary(array $households): array
    {
        return array_map(fn (ParsedHousehold $household) => [
            $household->name,
            array_map(fn ($guest) => $guest->fullName().($guest->isChild ? ' (child)' : ''), $household->guests),
        ], $households);
    }

    public function test_plain_list_with_household_names_children_and_shared_last_names(): void
    {
        $households = $this->reader()->fromText(<<<'TXT'
            Familie Meier: Heidi, Peter, Lina (Kind)
            Camille et Marco Rossi <camille@rossi.ch>
            Giulia Bernasconi
            TXT);

        $this->assertSame([
            ['Familie Meier', ['Heidi', 'Peter', 'Lina (child)']],
            ['Camille & Marco Rossi', ['Camille Rossi', 'Marco Rossi']],
            ['Giulia Bernasconi', ['Giulia Bernasconi']],
        ], $this->summary($households));
        $this->assertSame('camille@rossi.ch', $households[1]->email);
    }

    public function test_cells_pasted_from_excel_group_by_household_column(): void
    {
        $households = $this->reader()->fromText(
            "Vorname\tNachname\tHaushalt\tSprache\tKind\n".
            "Heidi\tMeier\tFamilie Meier\tDeutsch\t\n".
            "Lina\tMeier\tFamilie Meier\tDeutsch\tja\n".
            "Emma\tCarter\t\tEnglish\t\n"
        );

        $this->assertSame([
            ['Familie Meier', ['Heidi Meier', 'Lina Meier (child)']],
            ['Emma Carter', ['Emma Carter']],
        ], $this->summary($households));
        $this->assertSame(Locale::English, $households[1]->locale);
    }

    public function test_csv_from_windows_excel_with_semicolons_and_a_name_column(): void
    {
        $csv = mb_convert_encoding("Name;E-Mail\nZoë und Jürg Müller;zoe@mueller.ch\n", 'Windows-1252', 'UTF-8');

        $households = $this->reader()->fromFile(UploadedFile::fake()->createWithContent('gaeste.csv', $csv));

        $this->assertSame([['Zoë & Jürg Müller', ['Zoë Müller', 'Jürg Müller']]], $this->summary($households));
        $this->assertSame('zoe@mueller.ch', $households[0]->email);
    }

    public function test_contacts_export_from_a_phone(): void
    {
        $vcf = "BEGIN:VCARD\nVERSION:3.0\nN:Meier;Heidi;;;\nFN:Heidi Meier\nEMAIL;TYPE=HOME:heidi@meier.ch\nEND:VCARD\n".
            "BEGIN:VCARD\nVERSION:2.1\nFN;CHARSET=UTF-8;ENCODING=QUOTED-PRINTABLE:J=C3=BCrg M=C3=BCller\nEND:VCARD\n";

        $households = $this->reader()->fromFile(UploadedFile::fake()->createWithContent('kontakte.vcf', $vcf));

        $this->assertSame([['Heidi Meier', ['Heidi Meier']], ['Jürg Müller', ['Jürg Müller']]], $this->summary($households));
        $this->assertSame('heidi@meier.ch', $households[0]->email);
    }

    public function test_excel_workbook(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::OVERWRITE);
        $zip->addFromString('xl/sharedStrings.xml', '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><si><t>Name</t></si><si><t>Haushalt</t></si><si><t>Heidi Meier</t></si><si><r><t>Familie </t></r><r><t>Meier</t></r></si></sst>');
        $zip->addFromString('xl/worksheets/sheet1.xml', '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>'.
            '<row r="1"><c r="A1" t="s"><v>0</v></c><c r="B1" t="s"><v>1</v></c></row>'.
            '<row r="2"><c r="A2" t="s"><v>2</v></c><c r="B2" t="s"><v>3</v></c></row>'.
            '<row r="3"><c r="A3" t="inlineStr"><is><t>Peter Meier</t></is></c><c r="B3" t="s"><v>3</v></c></row>'.
            '</sheetData></worksheet>');
        $zip->close();

        $households = $this->reader()->fromFile(new UploadedFile($path, 'gaeste.xlsx', test: true));

        $this->assertSame([['Familie Meier', ['Heidi Meier', 'Peter Meier']]], $this->summary($households));
    }

    public function test_a_wedding_sized_excel_list_of_120_rows(): void
    {
        $rows = '<row r="1"><c r="A1" t="inlineStr"><is><t>Vorname</t></is></c><c r="B1" t="inlineStr"><is><t>Nachname</t></is></c><c r="C1" t="inlineStr"><is><t>Haushalt</t></is></c></row>';

        foreach (range(2, 121) as $row) {
            $household = 'Haushalt '.intdiv($row, 2);
            $rows .= "<row r=\"{$row}\"><c r=\"A{$row}\" t=\"inlineStr\"><is><t>Gast{$row}</t></is></c><c r=\"B{$row}\" t=\"inlineStr\"><is><t>Muster</t></is></c><c r=\"C{$row}\" t=\"inlineStr\"><is><t>{$household}</t></is></c></row>";
        }

        $path = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::OVERWRITE);
        $zip->addFromString('xl/worksheets/sheet1.xml', '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>'.$rows.'</sheetData></worksheet>');
        $zip->close();

        $households = $this->reader()->fromFile(new UploadedFile($path, 'gaeste.xlsx', test: true));

        $this->assertCount(60, $households);
        $this->assertSame(120, array_sum(array_map(fn (ParsedHousehold $household) => count($household->guests), $households)));
    }
}
