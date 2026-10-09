<?php

namespace App\Http\Controllers\Weddings;

use App\Actions\Weddings\SaveSetupStep;
use App\Actions\Weddings\SuggestTheme;
use App\Enums\SetupStep;
use App\Http\Controllers\Controller;
use App\Http\Requests\Weddings\UpdateSetupStepRequest;
use App\Http\Resources\WeddingSetupResource;
use App\Models\Wedding;
use App\Support\ProgrammePresets;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The seven setup steps: one page per step, saved on "Weiter".
 */
class WeddingSetupController extends Controller
{
    public function show(Wedding $wedding, SetupStep $step, SuggestTheme $suggest): Response|RedirectResponse
    {
        Gate::authorize('update', $wedding);

        if (! $wedding->isDraft()) {
            return to_route('dashboard');
        }

        if (! $step->isReachableFrom($wedding->setup_step)) {
            return to_route('weddings.setup.show', [$wedding, $wedding->setup_step ?? SetupStep::Couple]);
        }

        $wedding->load('events');

        return Inertia::render('setup/Step', [
            'wedding' => new WeddingSetupResource($wedding),
            'step' => $step,
            'steps' => collect(SetupStep::cases())->map(fn (SetupStep $item): array => [
                'value' => $item,
                'reachable' => $item->isReachableFrom($wedding->setup_step),
            ]),
            'presets' => fn (): ?array => $step === SetupStep::Programme ? ProgrammePresets::all() : null,
            'suggestion' => fn (): ?array => $step === SetupStep::Look ? $suggest->handle($wedding) : null,
        ]);
    }

    public function update(UpdateSetupStepRequest $request, Wedding $wedding, SetupStep $step, SaveSetupStep $save): RedirectResponse
    {
        if (! $wedding->isDraft()) {
            return to_route('dashboard');
        }

        $save->handle($wedding, $step, $request->validated());

        return to_route('weddings.setup.show', [$wedding, $step->next() ?? $step]);
    }
}
