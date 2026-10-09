<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * The couple sets the order of their blocks on the invitation.
 */
class ReorderContentBlocksController extends Controller
{
    public function __invoke(Request $request, Wedding $wedding): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'max:10'],
            'ids.*' => ['integer', Rule::exists('content_blocks', 'id')->where('wedding_id', $wedding->id)],
        ]);

        foreach (array_values($validated['ids']) as $position => $id) {
            $wedding->contentBlocks()->whereKey($id)->update(['position' => $position]);
        }

        return back();
    }
}
