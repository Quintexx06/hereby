<?php

namespace App\Actions\Kitchen;

use App\Enums\ResponseStatus;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use RuntimeException;

/**
 * One row per person for the caterer's spreadsheet: answers and menu per
 * part, allergies, and the household's answers. Semicolons and a BOM, so
 * Excel with Swiss settings opens it without an import dialog.
 */
class ExportGuestList
{
    public function __construct(private BuildKitchenSheet $sheet) {}

    /**
     * @return list<list<string>>
     */
    public function rows(Wedding $wedding): array
    {
        $events = $wedding->events()->orderBy('starts_at')->get();
        $menus = $this->sheet->menuLabels($wedding);
        $rows = [[
            'Haushalt', 'Vorname', 'Nachname', 'Kind', 'Begleitung',
            ...$events->map(fn (Event $event): string => $this->sheet->eventName($event))->all(),
            'Allergien', 'Shuttle-Plätze', 'Übernachtung', 'Liederwunsch',
        ]];

        foreach ($this->sheet->households($wedding) as $household) {
            foreach ($household->guests as $guest) {
                $rows[] = [
                    $household->name,
                    $guest->first_name,
                    (string) $guest->last_name,
                    $guest->is_child ? 'ja' : '',
                    $guest->is_plus_one ? 'ja' : '',
                    ...$events->map(fn (Event $event): string => $this->answer($household, $guest, $event, $menus))->all(),
                    (string) $guest->dietary_notes,
                    $household->shuttle_seats !== null ? (string) $household->shuttle_seats : '',
                    $household->needs_stay === null ? '' : ($household->needs_stay ? 'ja' : 'nein'),
                    (string) $household->song_wish,
                ];
            }
        }

        return $rows;
    }

    public function csv(Wedding $wedding): string
    {
        $handle = fopen('php://temp', 'r+');

        if ($handle === false) {
            throw new RuntimeException('Could not open a temporary stream for the CSV export.');
        }

        foreach ($this->rows($wedding) as $row) {
            fputcsv($handle, $row, ';', '"', '');
        }

        rewind($handle);
        $csv = (string) stream_get_contents($handle);
        fclose($handle);

        return "\u{FEFF}".$csv;
    }

    /**
     * @param  array<string, string>  $menus
     */
    private function answer(Household $household, Guest $guest, Event $event, array $menus): string
    {
        if (! $household->events->contains('id', $event->id)) {
            return '';
        }

        $response = $guest->responses->firstWhere('event_id', $event->id);

        return match ($response?->status) {
            ResponseStatus::Attending => trim('ja '.($response->menu_choice ? '('.($menus[$response->menu_choice] ?? $response->menu_choice).')' : '')),
            ResponseStatus::Declined => 'nein',
            default => 'offen',
        };
    }
}
