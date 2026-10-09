<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Support\WeddingCalendar;
use Illuminate\Http\Response;

/**
 * "In den Kalender": the household's parts of the day as an .ics file.
 */
class DownloadCalendarController extends Controller
{
    public function __invoke(Household $household): Response
    {
        return response(WeddingCalendar::for($household), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="hochzeit.ics"',
        ]);
    }
}
