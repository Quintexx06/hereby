<?php

namespace App\Http\Controllers\Witness;

use App\Http\Controllers\Controller;
use App\Models\ProgrammeItem;
use App\Models\Wedding;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The witnesses' private page (spec 2026-10-13): the evening's offers to
 * decide and order, behind one unguessable link the couple shares.
 */
class WitnessController extends Controller
{
    public function show(Wedding $wedding): Response
    {
        $token = $wedding->witnessToken();

        return Inertia::render('witness/Index', [
            'wedding' => [
                'coupleNames' => $wedding->couple_names,
                'date' => $wedding->wedding_date?->toDateString(),
                'budget' => $wedding->programme_minutes,
            ],
            'programme' => $wedding->programmeItems->map(fn (ProgrammeItem $item): array => [
                'id' => $item->id,
                'kind' => $item->kind->value,
                'title' => $item->title,
                'contact' => $item->contact_name,
                'notes' => $item->notes,
                'minutes' => $item->minutes,
                'status' => $item->status->value,
                'updateUrl' => route('witness.programme.update', [$token, $item]),
            ])->all(),
            'links' => [
                'reorder' => route('witness.programme.reorder', $token),
                'print' => route('witness.programme.print', $token),
            ],
        ]);
    }
}
