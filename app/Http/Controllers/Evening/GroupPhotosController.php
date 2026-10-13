<?php

namespace App\Http\Controllers\Evening;

use App\Http\Controllers\Controller;
use App\Http\Requests\Evening\UpdateGroupPhotosRequest;
use App\Models\GroupPhoto;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Gruppenfotos»: the couple lists the group photos and who belongs in
 * each; on the day the witnesses call them (spec 2026-10-13).
 */
class GroupPhotosController extends Controller
{
    public function edit(Wedding $wedding): Response
    {
        $unlocked = $wedding->includesEvening();

        return Inertia::render('evening/GroupPhotos', [
            'wedding' => ['id' => $wedding->id, 'couple_names' => $wedding->couple_names],
            'unlocked' => $unlocked,
            'groups' => $unlocked ? $wedding->groupPhotos()->with('households:id')->get()
                ->map(fn (GroupPhoto $group): array => [
                    'id' => $group->id,
                    'title' => $group->title,
                    'place' => $group->place,
                    'household_ids' => $group->households->modelKeys(),
                ])->all() : [],
            'households' => $unlocked ? $wedding->households()->orderBy('name')->get(['id', 'name'])
                ->map(fn (Household $household): array => ['id' => $household->id, 'name' => $household->name])
                ->all() : [],
        ]);
    }

    public function update(UpdateGroupPhotosRequest $request, Wedding $wedding): RedirectResponse
    {
        Gate::authorize('useEvening', $wedding);

        /** @var list<array{id: int|null, title: string, place: string|null, household_ids: list<int>}> $groups */
        $groups = $request->validated('groups');

        DB::transaction(function () use ($groups, $wedding): void {
            $keep = array_filter(array_column($groups, 'id'));
            $wedding->groupPhotos()->whereNotIn('id', $keep)->delete();

            foreach ($groups as $position => $row) {
                $group = $wedding->groupPhotos()->findOrNew($row['id'] ?? null);
                $group->fill([
                    'title' => trim($row['title']),
                    'place' => filled($row['place'] ?? null) ? trim((string) $row['place']) : null,
                    'position' => $position + 1,
                ]);
                $group->wedding_id = $wedding->id;
                $group->save();
                $group->households()->sync($row['household_ids']);
            }
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Gruppenfotos gespeichert.']);

        return back();
    }
}
