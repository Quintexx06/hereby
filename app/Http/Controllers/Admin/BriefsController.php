<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BriefStatus;
use App\Http\Controllers\Controller;
use App\Models\BriefPhoto;
use App\Models\DesignBrief;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Massanfertigung» for the team: every sent brief, newest work first, with
 * a status the couple sees and a note for them.
 */
class BriefsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Briefs', [
            'briefs' => DesignBrief::query()
                ->whereNotNull('submitted_at')
                ->with(['wedding:id,couple_names,wedding_date', 'photos'])
                ->orderByRaw("case status when 'submitted' then 0 when 'in_progress' then 1 else 2 end")
                ->orderBy('ready_by')
                ->get()
                ->map(fn (DesignBrief $brief): array => [
                    'id' => $brief->id,
                    'wedding_id' => $brief->wedding_id,
                    'couple_names' => $brief->wedding->couple_names,
                    'date' => $brief->wedding->wedding_date?->toDateString(),
                    'status' => $brief->status,
                    'wishes' => $brief->wishes,
                    'colors' => $brief->colors ?? [],
                    'mood' => $brief->mood,
                    'links' => $brief->links,
                    'ready_by' => $brief->ready_by?->toDateString(),
                    'submitted_at' => $brief->submitted_at?->toDateString(),
                    'team_note' => $brief->team_note,
                    'photos' => $brief->photos->map(fn (BriefPhoto $photo): string => route('weddings.bespoke.photos.show', [$brief->wedding_id, $photo]))->all(),
                ]),
            'statuses' => collect(BriefStatus::cases())
                ->reject(fn (BriefStatus $status): bool => $status === BriefStatus::Draft)
                ->map(fn (BriefStatus $status): array => ['value' => $status->value, 'label' => $status->label()])
                ->values(),
        ]);
    }

    public function update(Request $request, DesignBrief $brief): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(BriefStatus::class)->except([BriefStatus::Draft])],
            'team_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $brief->status = BriefStatus::from($data['status']);
        $brief->team_note = $data['team_note'] ?? null;
        $brief->save();

        return back();
    }
}
