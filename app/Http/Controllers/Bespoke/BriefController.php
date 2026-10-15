<?php

namespace App\Http\Controllers\Bespoke;

use App\Enums\Addon;
use App\Enums\BriefStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bespoke\UpdateBriefRequest;
use App\Mail\BespokeBriefSubmitted;
use App\Models\BriefPhoto;
use App\Models\DesignBrief;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Massanfertigung»: the couple tells us what they want (colours, mood,
 * photos, links), books it, and the team builds their site to the brief
 * (spec 2026-10-15-bespoke-and-design-freedom).
 */
class BriefController extends Controller
{
    public function show(Wedding $wedding): Response
    {
        $brief = $wedding->designBrief()->with('photos')->first();

        return Inertia::render('bespoke/Index', [
            'wedding' => [
                'id' => $wedding->id,
                'couple_names' => $wedding->couple_names,
                'owned' => $wedding->hasAddon(Addon::Bespoke),
                'buyable' => $wedding->canBuy(Addon::Bespoke),
                'price' => intdiv(Addon::Bespoke->price(), 100),
            ],
            'checkoutReady' => filled(config('services.stripe.secret')),
            'brief' => [
                'status' => $brief->status ?? BriefStatus::Draft,
                'status_label' => ($brief->status ?? BriefStatus::Draft)->label(),
                'wishes' => $brief?->wishes,
                'colors' => $brief->colors ?? [],
                'mood' => $brief?->mood,
                'links' => $brief?->links,
                'ready_by' => $brief?->ready_by?->toDateString(),
                'submitted_at' => $brief?->submitted_at?->toDateString(),
                'team_note' => $brief?->team_note,
                'photos' => $brief?->photos->map(fn (BriefPhoto $photo): array => [
                    'id' => $photo->id,
                    'url' => route('weddings.bespoke.photos.show', [$wedding, $photo]),
                ])->all() ?? [],
            ],
        ]);
    }

    public function update(UpdateBriefRequest $request, Wedding $wedding): RedirectResponse
    {
        $brief = $wedding->designBrief()->firstOrNew();
        $brief->fill([...$request->validated(), 'colors' => array_values(array_map('strtolower', $request->validated('colors') ?? []))]);
        $brief->wedding_id = $wedding->id;
        $brief->save();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Briefing gespeichert.']);

        return back();
    }

    /**
     * Sent once it is booked and says what the couple wants.
     */
    public function submit(Wedding $wedding): RedirectResponse
    {
        /** @var DesignBrief|null $brief */
        $brief = $wedding->designBrief;

        if (! $wedding->hasAddon(Addon::Bespoke)) {
            throw ValidationException::withMessages(['brief' => 'Bucht zuerst die Massanfertigung, dann schicken wir es los.']);
        }

        if (! $brief || blank($brief->wishes)) {
            throw ValidationException::withMessages(['wishes' => 'Schreibt uns ein paar Sätze zu euren Wünschen.']);
        }

        if ($brief->status === BriefStatus::Draft) {
            $brief->forceFill(['status' => BriefStatus::Submitted, 'submitted_at' => now()])->save();

            if (filled(config('hereby.inbox'))) {
                Mail::to((string) config('hereby.inbox'))->queue(new BespokeBriefSubmitted($brief));
            }
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Danke! Wir melden uns innert zwei Arbeitstagen.']);

        return back();
    }
}
