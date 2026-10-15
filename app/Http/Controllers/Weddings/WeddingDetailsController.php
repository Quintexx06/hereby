<?php

namespace App\Http\Controllers\Weddings;

use App\Actions\Weddings\SaveSetupStep;
use App\Enums\SetupStep;
use App\Http\Controllers\Controller;
use App\Http\Requests\Weddings\UpdateSetupStepRequest;
use App\Http\Resources\WeddingSetupResource;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * "Eckdaten": the setup questions again, for a wedding that is already live.
 * One tab per step, saved on its own, never losing guests' answers.
 */
class WeddingDetailsController extends Controller
{
    public function show(Wedding $wedding, SetupStep $step): Response|RedirectResponse
    {
        if ($wedding->isDraft()) {
            return to_route('weddings.setup.show', [$wedding, $wedding->setup_step ?? SetupStep::Couple]);
        }

        abort_if($step === SetupStep::Review, 404);

        $wedding->load('events');

        return Inertia::render('weddings/Details', [
            'wedding' => new WeddingSetupResource($wedding),
            'step' => $step,
        ]);
    }

    public function update(UpdateSetupStepRequest $request, Wedding $wedding, SetupStep $step, SaveSetupStep $save): RedirectResponse
    {
        abort_if($wedding->isDraft() || $step === SetupStep::Review, 404);

        $save->handle($wedding, $step, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Gespeichert. Eure Gäste sehen es beim nächsten Besuch.']);

        return back();
    }
}
