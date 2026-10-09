<?php

namespace App\Http\Controllers\Kitchen;

use App\Actions\Kitchen\BuildKitchenSheet;
use App\Actions\Kitchen\ExportGuestList;
use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * "Küche & Service": the final numbers for the venue (roadmap 1.12). The
 * page shows counts only; allergy notes appear only in the printable sheet
 * and the export, rendered on the server for the owner (CLAUDE.md rule 9).
 */
class KitchenController extends Controller
{
    public function index(Wedding $wedding, BuildKitchenSheet $sheet): Response
    {
        return Inertia::render('kitchen/Index', [
            'wedding' => ['id' => $wedding->id, 'couple_names' => $wedding->couple_names],
            ...$sheet->summary($wedding),
        ]);
    }

    public function sheet(Wedding $wedding, BuildKitchenSheet $sheet): HttpResponse
    {
        return response()
            ->view('kitchen.sheet', [
                'wedding' => $wedding,
                'summary' => $sheet->summary($wedding),
                'allergies' => $sheet->allergies($wedding),
                'eventName' => fn (array $event): string => $event['name'] ?? (string) __("invitation.event_types.{$event['type']}", [], 'de_CH'),
            ])
            ->header('Cache-Control', 'no-store, private');
    }

    public function csv(Wedding $wedding, ExportGuestList $export): StreamedResponse
    {
        $csv = $export->csv($wedding);

        return response()->streamDownload(function () use ($csv): void {
            echo $csv;
        }, 'gaeste-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, private',
        ]);
    }
}
