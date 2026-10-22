<?php

namespace App\Http\Controllers\Partners;

use App\Actions\Partners\BuildTourPreview;
use App\Enums\WeddingTemplate;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvitationResource;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Vorschau für die Besichtigung», for Showcase venues: the form, and with
 * two names and a date the finished-looking site at this venue.
 */
class TourPreviewController extends Controller
{
    public function __invoke(Request $request, BuildTourPreview $preview): Response
    {
        /** @var Partner $venue */
        $venue = $request->user()?->partner;
        abort_unless($venue->isVenue() && $venue->is_showcase, 403);

        if (! $request->filled('erste')) {
            return Inertia::render('partner/TourPreview', ['venue' => $venue->name, 'code' => $venue->code]);
        }

        $input = $request->validate([
            'erste' => ['required', 'string', 'max:60'],
            'zweite' => ['required', 'string', 'max:60'],
            'datum' => ['required', 'date', 'after:today'],
            'vorlage' => ['nullable', Rule::enum(WeddingTemplate::class)],
        ]);

        App::setLocale('de_CH');

        return Inertia::render('invitation/Show', [
            'invitation' => new InvitationResource($preview->handle($venue, [
                'first' => $input['erste'],
                'second' => $input['zweite'],
                'date' => $input['datum'],
                'template' => $input['vorlage'] ?? WeddingTemplate::Salon->value,
            ])),
            'replied' => false,
            'preview' => true,
            'previewLanguages' => [],
        ]);
    }
}
