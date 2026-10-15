<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\UpdateWeddingContactRequest;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

/**
 * The footer of the couple's site: who guests write to with questions.
 */
class WeddingContactController extends Controller
{
    public function __invoke(UpdateWeddingContactRequest $request, Wedding $wedding): RedirectResponse
    {
        $wedding->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Fusszeile gespeichert.']);

        return back();
    }
}
