<?php

namespace App\Http\Controllers\Witness;

use App\Enums\Addon;
use App\Http\Controllers\Controller;
use App\Models\GroupPhoto;
use App\Models\NewspaperArticle;
use App\Models\ProgrammeItem;
use App\Models\Wedding;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The witnesses' private page (spec 2026-10-13): the evening's offers to
 * decide and order, the group photos to call and the newspaper to edit,
 * behind one unguessable link the couple shares.
 */
class WitnessController extends Controller
{
    public function show(Wedding $wedding): Response
    {
        $token = $wedding->witnessToken();
        $evening = $wedding->includesEvening();
        $newspaper = $wedding->hasAddon(Addon::Newspaper);

        return Inertia::render('witness/Index', [
            'wedding' => [
                'coupleNames' => $wedding->couple_names,
                'date' => $wedding->wedding_date?->toDateString(),
                'budget' => $wedding->programme_minutes,
            ],
            'evening' => $evening,
            'newspaper' => $newspaper,
            'programme' => ! $evening ? [] : $wedding->programmeItems->map(fn (ProgrammeItem $item): array => [
                'id' => $item->id,
                'kind' => $item->kind->value,
                'title' => $item->title,
                'contact' => $item->contact_name,
                'notes' => $item->notes,
                'minutes' => $item->minutes,
                'status' => $item->status->value,
                'updateUrl' => route('witness.programme.update', [$token, $item]),
            ])->all(),
            'articles' => ! $newspaper ? [] : $wedding->newspaperArticles->map(fn (NewspaperArticle $article): array => [
                'id' => $article->id,
                'author' => $article->author_name,
                'title' => $article->title,
                'body' => $article->body,
                'photoUrl' => $article->photo_path ? route('witness.newspaper.photo', [$token, $article]) : null,
                'deleteUrl' => route('witness.newspaper.destroy', [$token, $article]),
            ])->all(),
            'groups' => ! $evening ? [] : $wedding->groupPhotos()->with('households:id,name')->get()->map(fn (GroupPhoto $group): array => [
                'id' => $group->id,
                'title' => $group->title,
                'place' => $group->place,
                'households' => $group->households->pluck('name')->all(),
                'calledAt' => $group->called_at?->toIso8601String(),
                'callUrl' => route('witness.group-photos.call', [$token, $group]),
            ])->all(),
            'links' => [
                'reorder' => $evening ? route('witness.programme.reorder', $token) : null,
                'print' => $evening ? route('witness.programme.print', $token) : null,
                'newspaper' => $newspaper ? route('witness.newspaper.print', $token) : null,
            ],
        ]);
    }
}
