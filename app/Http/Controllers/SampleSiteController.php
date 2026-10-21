<?php

namespace App\Http\Controllers;

use App\Actions\Partners\BuildVenueDemo;
use App\Enums\PartnerType;
use App\Enums\ResponseStatus;
use App\Enums\WeddingTemplate;
use App\Http\Resources\InvitationResource;
use App\Http\Resources\ReplyFormResource;
use App\Models\Partner;
use App\Support\WeddingCalendar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Live ansehen» on the landing: each template as a finished site for a
 * sample couple at a sample venue, open to everyone, with the real reply
 * form. Built in memory like the venue demo; a reply is shown once and
 * never stored.
 */
class SampleSiteController extends Controller
{
    public function show(Request $request, WeddingTemplate $template, BuildVenueDemo $demo): Response
    {
        App::setLocale('de_CH');
        /** @var list<array{event_id: int, status: string, menu?: string|null}>|null $answers */
        $answers = $request->session()->get('sample_answers');

        return Inertia::render('invitation/Show', [
            'invitation' => (new InvitationResource($demo->handle($this->venue(), $template, $answers)))
                ->withLinks($this->links($template)),
            'replied' => $answers !== null,
            'preview' => true,
            'previewLanguages' => [],
            'demo' => [
                'sample' => true,
                'venue' => null,
                'template' => $template->value,
                'more' => route('register'),
                'links' => collect(WeddingTemplate::cases())
                    ->mapWithKeys(fn (WeddingTemplate $case): array => [$case->value => route('samples.show', $case)])
                    ->all(),
            ],
        ]);
    }

    public function reply(WeddingTemplate $template, BuildVenueDemo $demo): Response
    {
        App::setLocale('de_CH');
        $links = $this->links($template);

        return Inertia::render('invitation/Reply', [
            'reply' => (new ReplyFormResource($demo->handle($this->venue(), $template)))
                ->withLinks(['invitation' => $links['invitation'], 'reply' => route('samples.reply.update', $template)]),
        ]);
    }

    public function update(Request $request, WeddingTemplate $template): RedirectResponse
    {
        $answers = $request->validate([
            'answers' => ['required', 'array', 'max:10'],
            'answers.*.event_id' => ['required', 'integer', 'between:1,4'],
            'answers.*.status' => ['required', Rule::enum(ResponseStatus::class)],
            'answers.*.menu' => ['nullable', 'string', 'max:40'],
        ])['answers'];

        return to_route('samples.show', $template)->with('sample_answers', array_values($answers));
    }

    public function calendar(WeddingTemplate $template, BuildVenueDemo $demo): HttpResponse
    {
        return response(WeddingCalendar::for($demo->handle($this->venue(), $template), route('samples.show', $template)), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="hochzeit.ics"',
        ]);
    }

    /**
     * @return array{invitation: string, reply: string, calendar: string}
     */
    private function links(WeddingTemplate $template): array
    {
        return [
            'invitation' => route('samples.show', $template),
            'reply' => route('samples.reply', $template),
            'calendar' => route('samples.calendar', $template),
        ];
    }

    /**
     * A sample venue on the lake, never stored: the site's place, travel and stay.
     */
    private function venue(): Partner
    {
        $venue = new Partner([
            'type' => PartnerType::Venue,
            'name' => 'Seehotel Vitznau',
            'address' => 'Seestrasse 1',
            'postcode' => '6354',
            'town' => 'Vitznau',
            'about' => 'Direkt am Vierwaldstättersee: Trauung auf der Wiese am Ufer, Apéro auf der Terrasse, Abendessen im Salon mit Blick auf die Rigi.',
            'travel' => 'Mit dem Zug bis Luzern, dann mit dem Schiff nach Vitznau (55 Minuten). Das letzte Schiff zurück fährt um 00:40.',
            'stay' => 'Wir haben Zimmer im Haus reserviert. Fragt an der Rezeption nach der Hochzeit von Anna und Luca.',
        ]);
        $venue->setRelation('photos', new Collection);

        return $venue;
    }
}
