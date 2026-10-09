<?php

namespace App\Http\Controllers\Content;

use App\Enums\ContentBlockType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Content\StoreContentBlockRequest;
use App\Http\Requests\Content\UpdateContentBlockRequest;
use App\Models\ContentBlock;
use App\Models\Event;
use App\Models\Wedding;
use App\Support\WeddingLanguages;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * "Inhalte": the couple's story, venue note, dress code and FAQ.
 */
class ContentBlockController extends Controller
{
    public function index(Wedding $wedding): Response
    {
        return Inertia::render('content/Index', [
            'wedding' => [
                'id' => $wedding->id,
                'languages' => WeddingLanguages::of($wedding),
                'default_locale' => $wedding->default_locale,
                'venue' => $wedding->venue_name,
            ],
            'blocks' => $wedding->contentBlocks->map(fn (ContentBlock $block): array => [
                'id' => $block->id,
                'type' => $block->type,
                'event_id' => $block->event_id,
                'content' => (object) $block->content,
            ]),
            'events' => $wedding->events()->orderBy('starts_at')->get()->map(fn (Event $event): array => [
                'id' => $event->id,
                'type' => $event->type,
                'name' => $event->name,
                'starts_at' => $event->starts_at->toIso8601String(),
            ]),
        ]);
    }

    public function store(StoreContentBlockRequest $request, Wedding $wedding): RedirectResponse
    {
        $wedding->contentBlocks()->create([
            'type' => ContentBlockType::from($request->string('type')->value()),
            'position' => (int) $wedding->contentBlocks()->max('position') + 1,
            'content' => [],
        ]);

        return back();
    }

    public function update(UpdateContentBlockRequest $request, Wedding $wedding, ContentBlock $contentBlock): RedirectResponse
    {
        $contentBlock->update([
            'event_id' => $request->filled('event_id') ? $request->integer('event_id') : null,
            'content' => $request->content(),
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Gespeichert.']);

        return back();
    }

    public function destroy(Wedding $wedding, ContentBlock $contentBlock): RedirectResponse
    {
        $contentBlock->delete();

        return back();
    }
}
