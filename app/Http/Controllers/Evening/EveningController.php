<?php

namespace App\Http\Controllers\Evening;

use App\Enums\ProgrammeStatus;
use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Der Abend»: one page that leads to everything for the wedding day
 * itself (Atelier and up, spec 2026-10-13), each with where it stands.
 */
class EveningController extends Controller
{
    public function show(Wedding $wedding): Response
    {
        $guests = $wedding->households()->join('guests', 'guests.household_id', '=', 'households.id');

        return Inertia::render('evening/Index', [
            'wedding' => ['id' => $wedding->id, 'couple_names' => $wedding->couple_names],
            'unlocked' => $wedding->includesEvening(),
            'status' => [
                'seating' => [
                    'published' => $wedding->seating_published_at !== null,
                    'seated' => (clone $guests)->whereNotNull('guests.table_name')->count(),
                    'guests' => $guests->count(),
                ],
                'wayHome' => count($wedding->way_home ?? []),
                'programme' => [
                    'total' => $wedding->programmeItems()->count(),
                    'accepted' => $wedding->programmeItems()->where('status', ProgrammeStatus::Accepted)->count(),
                ],
            ],
        ]);
    }
}
