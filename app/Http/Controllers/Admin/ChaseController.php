<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Addon;
use App\Enums\Plan;
use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Wir fassen nach» (spec 2026-10-13): for weddings with the service whose
 * deadline is within two weeks, every household that hasn't answered, with
 * its contacts, for the team to call or write. Marked once reached.
 */
class ChaseController extends Controller
{
    /** How long before the deadline the team starts, in days. */
    public const int DAYS = 14;

    public function index(): Response
    {
        $today = now('Europe/Zurich')->toDateString();

        $weddings = Wedding::query()
            ->whereNotNull('paid_at')
            ->whereDate('rsvp_deadline', '>=', $today)
            ->whereDate('rsvp_deadline', '<=', now('Europe/Zurich')->addDays(self::DAYS)->toDateString())
            ->where(fn (Builder $query) => $query
                ->where('plan', Plan::Couture)
                ->orWhereHas('addons', fn (Builder $addons) => $addons->where('addon', Addon::ChaseService)))
            ->with(['owner:id,email', 'households' => fn ($query) => $query->whereNull('responded_at')->orderBy('name')])
            ->orderBy('rsvp_deadline')
            ->get();

        return Inertia::render('admin/Chase', [
            'weddings' => $weddings->map(fn (Wedding $wedding): array => [
                'id' => $wedding->id,
                'couple_names' => $wedding->couple_names,
                'email' => $wedding->owner->email,
                'deadline' => $wedding->rsvp_deadline?->toDateString(),
                'households' => $wedding->households->map(fn (Household $household): array => [
                    'id' => $household->id,
                    'name' => $household->name,
                    'email' => $household->email,
                    'phone' => $household->phone,
                    'opened' => $household->opened_at !== null,
                    'chased_at' => $household->chased_at?->toIso8601String(),
                ])->all(),
            ])->all(),
        ]);
    }

    public function update(Request $request, Household $household): RedirectResponse
    {
        $chased = $request->validate(['chased' => ['required', 'boolean']])['chased'];

        $household->forceFill(['chased_at' => $chased ? now() : null])->save();

        return back();
    }
}
