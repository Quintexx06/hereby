<?php

namespace App\Http\Controllers\Evening;

use App\Http\Controllers\Controller;
use App\Http\Requests\Evening\UpdateSeatingRequest;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Tischplan»: the couple seats their guests and writes one line about
 * each for their table mates (Atelier and up, spec 2026-10-13).
 */
class SeatingController extends Controller
{
    public function edit(Wedding $wedding): Response
    {
        $unlocked = $wedding->includesEvening();

        return Inertia::render('evening/Seating', [
            'wedding' => ['id' => $wedding->id, 'couple_names' => $wedding->couple_names],
            'unlocked' => $unlocked,
            'published' => $wedding->seating_published_at !== null,
            'households' => $unlocked ? $wedding->households()
                ->with('guests:id,household_id,first_name,last_name,table_name,table_note')
                ->orderBy('name')
                ->get()
                ->map(fn (Household $household): array => [
                    'id' => $household->id,
                    'name' => $household->name,
                    'guests' => $household->guests->map(fn (Guest $guest): array => [
                        'id' => $guest->id,
                        'first_name' => $guest->first_name,
                        'last_name' => $guest->last_name,
                        'table_name' => $guest->table_name,
                        'table_note' => $guest->table_note,
                    ])->all(),
                ])->all() : [],
        ]);
    }

    public function update(UpdateSeatingRequest $request, Wedding $wedding): RedirectResponse
    {
        Gate::authorize('useEvening', $wedding);

        DB::transaction(function () use ($request, $wedding): void {
            /** @var list<array{id: int, table_name: string|null, table_note: string|null}> $seats */
            $seats = $request->validated('guests');

            foreach ($seats as $seat) {
                Guest::query()->whereKey($seat['id'])->update([
                    'table_name' => filled($seat['table_name'] ?? null) ? trim((string) $seat['table_name']) : null,
                    'table_note' => filled($seat['table_note'] ?? null) ? trim((string) $seat['table_note']) : null,
                ]);
            }

            $wedding->forceFill([
                'seating_published_at' => $request->boolean('published') ? ($wedding->seating_published_at ?? now()) : null,
            ])->save();
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Tischplan gespeichert.']);

        return back();
    }
}
