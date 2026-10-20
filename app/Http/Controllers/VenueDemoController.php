<?php

namespace App\Http\Controllers;

use App\Actions\Partners\BuildVenueDemo;
use App\Enums\WeddingTemplate;
use App\Http\Resources\InvitationResource;
use App\Models\Partner;
use App\Support\VenueDemoLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

/**
 * A sales demo for a venue we want to win: its own wedding site, built from
 * its profile like the tour preview, behind a signed link that works for
 * 90 days without an account. The template can be switched in the link,
 * and the visitor can reply like a guest (VenueDemoReplyController).
 */
class VenueDemoController extends Controller
{
    public function __invoke(Request $request, Partner $partner, BuildVenueDemo $demo): Response
    {
        abort_unless($request->hasValidSignatureWhileIgnoring(['vorlage']), 403);
        abort_unless($partner->isVenue(), 404);

        App::setLocale('de_CH');
        $template = WeddingTemplate::tryFrom((string) $request->query('vorlage')) ?? WeddingTemplate::Atelier;
        /** @var list<array{event_id: int, status: string, menu?: string|null}>|null $answers */
        $answers = $request->session()->get('demo_answers');
        $links = VenueDemoLinks::for($partner, $template, $request->integer('expires'));

        return Inertia::render('invitation/Show', [
            'invitation' => (new InvitationResource($demo->handle($partner, $template, $answers)))
                ->withLinks(['invitation' => $links['invitation'], 'reply' => $links['reply'], 'calendar' => $links['calendar']]),
            'replied' => $answers !== null,
            'preview' => true,
            'previewLanguages' => [],
            'demo' => [
                'venue' => $partner->name,
                'template' => $template->value,
                'more' => route('topics.show', 'fuer-locations'),
            ],
        ]);
    }
}
