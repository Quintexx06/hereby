<?php

namespace App\Http\Controllers;

use App\Actions\Partners\BuildTourPreview;
use App\Enums\WeddingTemplate;
use App\Http\Resources\InvitationResource;
use App\Models\Partner;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

/**
 * A sales demo for a venue we want to win: its own wedding site, built from
 * its profile like the tour preview, behind a signed link that works for
 * 90 days without an account. The template can be switched in the link.
 */
class VenueDemoController extends Controller
{
    public const DAYS_VALID = 90;

    public function __invoke(Request $request, Partner $partner, BuildTourPreview $preview): Response
    {
        abort_unless($request->hasValidSignatureWhileIgnoring(['vorlage']), 403);
        abort_unless($partner->isVenue(), 404);

        App::setLocale('de_CH');
        $template = WeddingTemplate::tryFrom((string) $request->query('vorlage')) ?? WeddingTemplate::Atelier;

        return Inertia::render('invitation/Show', [
            'invitation' => new InvitationResource($preview->handle($partner, [
                'first' => 'Anna',
                'second' => 'Luca',
                'date' => self::exampleDate()->toDateString(),
                'template' => $template->value,
            ])),
            'replied' => false,
            'preview' => true,
            'previewLanguages' => [],
            'demo' => [
                'venue' => $partner->name,
                'template' => $template->value,
                'more' => route('topics.show', 'fuer-locations'),
            ],
        ]);
    }

    /**
     * A Saturday in June, at least four months ahead: a believable date.
     */
    public static function exampleDate(): CarbonImmutable
    {
        $june = CarbonImmutable::now('Europe/Zurich')->setDate((int) now()->format('Y'), 6, 1)->startOfDay();

        if ($june->lt(now()->addMonths(4))) {
            $june = $june->addYear();
        }

        return $june->next(CarbonImmutable::SATURDAY)->addWeeks(2);
    }
}
