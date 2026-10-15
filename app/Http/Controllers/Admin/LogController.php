<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Protokoll»: warnings and errors from the running app, newest first, so
 * the team sees problems before couples write in.
 */
class LogController extends Controller
{
    public function index(Request $request): Response
    {
        $show = in_array($request->query('zeigen'), ['fehler', 'erledigt'], true) ? $request->query('zeigen') : 'offen';

        $entries = LogEntry::query()
            ->when($show === 'erledigt', fn ($query) => $query->whereNotNull('resolved_at'), fn ($query) => $query->open())
            ->when($show === 'fehler', fn ($query) => $query->errors())
            ->latest('last_seen_at')
            ->limit(100)
            ->get();

        return Inertia::render('admin/Log', [
            'show' => $show,
            'counts' => [
                'offen' => LogEntry::query()->open()->count(),
                'fehler' => LogEntry::query()->open()->errors()->count(),
                'erledigt' => LogEntry::query()->whereNotNull('resolved_at')->count(),
            ],
            'entries' => $entries->map(fn (LogEntry $entry): array => [
                'id' => $entry->id,
                'level' => $entry->level,
                'is_error' => $entry->isError(),
                'message' => $entry->message,
                'exception' => $entry->exception,
                'location' => $entry->location,
                'url' => $entry->url,
                'trace' => $entry->trace,
                'count' => $entry->count,
                'first_seen' => $entry->first_seen_at->toIso8601String(),
                'last_seen' => $entry->last_seen_at->toIso8601String(),
                'resolved' => $entry->resolved_at !== null,
            ]),
        ]);
    }

    /**
     * Marks a problem as handled; it opens again if it shows again.
     */
    public function update(LogEntry $logEntry): RedirectResponse
    {
        $logEntry->resolved_at = $logEntry->resolved_at ? null : now();
        $logEntry->save();

        return back();
    }

    public function destroy(): RedirectResponse
    {
        LogEntry::query()->whereNotNull('resolved_at')->delete();

        return back();
    }
}
