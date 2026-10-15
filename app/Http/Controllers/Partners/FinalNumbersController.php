<?php

namespace App\Http\Controllers\Partners;

use App\Actions\Kitchen\BuildKitchenSheet;
use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * The final numbers for a venue or planner: the couple's own printable
 * kitchen sheet, rendered on the server (allergies never reach Vue).
 */
class FinalNumbersController extends Controller
{
    public function __invoke(Request $request, Wedding $wedding, BuildKitchenSheet $sheet): Response
    {
        abort_unless($request->user()?->partner?->type->seesFinalNumbers() === true, 403);

        return response()
            ->view('kitchen.sheet', [
                'wedding' => $wedding,
                'summary' => $sheet->summary($wedding),
                'allergies' => $sheet->allergies($wedding),
                'eventName' => fn (array $event): string => $event['name'] ?? (string) __("invitation.event_types.{$event['type']}", [], 'de_CH'),
            ])
            ->header('Cache-Control', 'no-store, private');
    }
}
