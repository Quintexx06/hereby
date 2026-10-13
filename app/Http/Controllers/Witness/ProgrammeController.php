<?php

namespace App\Http\Controllers\Witness;

use App\Enums\ProgrammeStatus;
use App\Http\Controllers\Controller;
use App\Models\ProgrammeItem;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * The witnesses decide on each offer, order the evening, and print the
 * running order for the MC and the band (spec 2026-10-13).
 */
class ProgrammeController extends Controller
{
    public function update(Request $request, Wedding $wedding, ProgrammeItem $programmeItem): RedirectResponse
    {
        $validated = $request->validate(['status' => ['required', Rule::enum(ProgrammeStatus::class)]]);

        $programmeItem->forceFill(['status' => $validated['status']])->save();

        return back();
    }

    public function reorder(Request $request, Wedding $wedding): RedirectResponse
    {
        /** @var list<int> $ids */
        $ids = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', Rule::exists('programme_items', 'id')->where('wedding_id', $wedding->id)],
        ])['ids'];

        DB::transaction(function () use ($ids, $wedding): void {
            foreach (array_values($ids) as $position => $id) {
                $wedding->programmeItems()->whereKey($id)->update(['position' => $position + 1]);
            }
        });

        return back();
    }

    public function print(Wedding $wedding): Response
    {
        $accepted = $wedding->programmeItems->where('status', ProgrammeStatus::Accepted)->values();

        return response()
            ->view('witness.programme', [
                'wedding' => $wedding,
                'items' => $accepted,
                'total' => $accepted->sum('minutes'),
            ])
            ->header('Cache-Control', 'no-store, private');
    }
}
