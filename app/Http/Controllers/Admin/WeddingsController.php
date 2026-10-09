<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\SetUpCouple;
use App\Enums\LookStyle;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCoupleRequest;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The team's view of every wedding, and setting one up for a couple
 * (roadmap 1.1). Editing happens on the couple's own pages (Gate::before).
 */
class WeddingsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Weddings', [
            'weddings' => Wedding::query()
                ->with('owner:id,email')
                ->withCount(['households', 'households as answered_count' => fn (Builder $query) => $query->whereNotNull('responded_at')])
                ->latest()
                ->get()
                ->map(fn (Wedding $wedding): array => [
                    'id' => $wedding->id,
                    'couple_names' => $wedding->couple_names ?: '–',
                    'email' => $wedding->owner->email,
                    'status' => $wedding->status,
                    'setup_step' => $wedding->setup_step,
                    'date' => $wedding->wedding_date?->toDateString(),
                    'households' => (int) $wedding->households_count,
                    'answered' => (int) $wedding->getAttribute('answered_count'),
                    'look_styles' => $wedding->look_styles?->map(fn (LookStyle $style): string => $style->value)->values() ?? [],
                    'look_wishes' => $wedding->look_wishes,
                    'created_at' => $wedding->created_at?->toDateString(),
                ]),
        ]);
    }

    public function store(StoreCoupleRequest $request, SetUpCouple $setUp): RedirectResponse
    {
        /** @var array{email: string, partner_one: string, partner_two: string} $data */
        $data = $request->safe()->only(['email', 'partner_one', 'partner_two']);
        $wedding = $setUp->handle($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$wedding->couple_names} angelegt. Die Einladung ist unterwegs."]);

        return back();
    }
}
