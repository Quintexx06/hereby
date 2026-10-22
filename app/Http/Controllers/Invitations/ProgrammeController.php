<?php

namespace App\Http\Controllers\Invitations;

use App\Enums\ProgrammeKind;
use App\Enums\ProgrammeStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invitations\StoreProgrammeItemRequest;
use App\Models\Household;
use App\Models\ProgrammeItem;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Etwas vorbereiten?»: a household offers a speech, game or song from its
 * personal link, and sees what it offered (spec 2026-10-13).
 */
class ProgrammeController extends Controller
{
    public function show(Household $household): Response
    {
        return Inertia::render('invitation/Programme', [
            'wedding' => [
                'coupleNames' => $household->wedding->couple_names,
                'template' => $household->wedding->template,
            ],
            'contact' => $household->name,
            'sent' => (bool) session('offered'),
            'kinds' => array_map(fn (ProgrammeKind $kind): string => $kind->value, ProgrammeKind::cases()),
            'items' => $household->programmeItems->map(fn (ProgrammeItem $item): array => [
                'id' => $item->id,
                'kind' => $item->kind->value,
                'title' => $item->title,
                'minutes' => $item->minutes,
                'status' => $item->status->value,
                'withdrawUrl' => $item->status === ProgrammeStatus::Pending
                    ? route('invitation.programme.destroy', [$household, $item])
                    : null,
            ])->all(),
            'links' => [
                'invitation' => route('invitation.show', $household),
                'store' => route('invitation.programme.store', $household),
            ],
        ]);
    }

    public function store(StoreProgrammeItemRequest $request, Household $household): RedirectResponse
    {
        $item = new ProgrammeItem($request->validated());
        $item->wedding_id = $household->wedding_id;
        $item->household_id = $household->id;
        $item->position = (int) $household->wedding->programmeItems()->max('position') + 1;
        $item->save();

        return to_route('invitation.programme', $household)->with('offered', true);
    }

    /**
     * Withdraw an offer while the witnesses haven't decided on it yet.
     */
    public function destroy(Household $household, ProgrammeItem $programmeItem): RedirectResponse
    {
        abort_unless($programmeItem->status === ProgrammeStatus::Pending, 403);

        $programmeItem->delete();

        return to_route('invitation.programme', $household);
    }
}
