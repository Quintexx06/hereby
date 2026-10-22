<?php

namespace App\Http\Controllers;

use App\Actions\Weddings\BuildWeddingOverview;
use App\Enums\AdminView;
use App\Enums\SetupStep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The couple's home: start the setup, continue it, or run the wedding.
 */
class DashboardController extends Controller
{
    public function __invoke(Request $request, BuildWeddingOverview $overview): Response|RedirectResponse
    {
        // Venues, photographers and planners have their own start page.
        if ($request->user()?->partner_id) {
            return to_route('partner.calendar');
        }

        // Admins in the team view start on the team's list of weddings.
        if (AdminView::for($request) === AdminView::Team) {
            return to_route('admin.weddings.index');
        }

        $wedding = $request->user()->weddings()->first();

        return Inertia::render('Dashboard', [
            'wedding' => $wedding ? [
                'id' => $wedding->id,
                'status' => $wedding->status,
                'couple_names' => $wedding->couple_names,
                'date' => $wedding->wedding_date?->toDateString(),
                'rsvp_deadline' => $wedding->rsvp_deadline?->toDateString(),
                'venue' => $wedding->venue_name,
                'template' => $wedding->template,
                'setup_step' => $wedding->setup_step,
                'setup_position' => $wedding->setup_step?->position(),
                'setup_total' => count(SetupStep::cases()),
            ] : null,
            'overview' => fn () => $wedding && ! $wedding->isDraft() ? $overview->handle($wedding) : null,
        ]);
    }
}
