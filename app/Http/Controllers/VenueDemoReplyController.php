<?php

namespace App\Http\Controllers;

use App\Actions\Partners\BuildVenueDemo;
use App\Enums\ResponseStatus;
use App\Enums\WeddingTemplate;
use App\Http\Resources\ReplyFormResource;
use App\Models\Partner;
use App\Support\VenueDemoLinks;
use App\Support\WeddingCalendar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The guest's side of a venue demo: the real 60-second reply form and the
 * calendar file. A reply is shown once on the demo, never stored.
 */
class VenueDemoReplyController extends Controller
{
    public function show(Request $request, Partner $partner, BuildVenueDemo $demo): Response
    {
        $template = $this->open($request, $partner);
        $links = VenueDemoLinks::for($partner, $template, $request->integer('expires'));

        return Inertia::render('invitation/Reply', [
            'reply' => (new ReplyFormResource($demo->handle($partner, $template)))
                ->withLinks(['invitation' => $links['invitation'], 'reply' => $links['update']]),
        ]);
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $template = $this->open($request, $partner);
        $answers = $request->validate([
            'answers' => ['required', 'array', 'max:10'],
            'answers.*.event_id' => ['required', 'integer', 'between:1,4'],
            'answers.*.status' => ['required', Rule::enum(ResponseStatus::class)],
            'answers.*.menu' => ['nullable', 'string', 'max:40'],
        ])['answers'];

        $links = VenueDemoLinks::for($partner, $template, $request->integer('expires'));

        return redirect()->to($links['invitation'])->with('demo_answers', array_values($answers));
    }

    public function calendar(Request $request, Partner $partner, BuildVenueDemo $demo): HttpResponse
    {
        $template = $this->open($request, $partner);
        $links = VenueDemoLinks::for($partner, $template, $request->integer('expires'));

        return response(WeddingCalendar::for($demo->handle($partner, $template), $links['invitation']), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="hochzeit.ics"',
        ]);
    }

    private function open(Request $request, Partner $partner): WeddingTemplate
    {
        abort_unless($request->hasValidSignature(), 403);
        abort_unless($partner->isVenue(), 404);
        App::setLocale('de_CH');

        return WeddingTemplate::fromKey($request->query('vorlage')) ?? WeddingTemplate::Salon;
    }
}
