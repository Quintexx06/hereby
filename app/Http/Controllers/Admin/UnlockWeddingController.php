<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Billing\MarkWeddingPaid;
use App\Enums\Plan;
use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

/**
 * The team unlocks a plan paid outside Stripe (invoice, Couture).
 */
class UnlockWeddingController extends Controller
{
    public function __invoke(Request $request, Wedding $wedding, MarkWeddingPaid $markPaid): RedirectResponse
    {
        $plan = Plan::from($request->validate(['plan' => ['required', Rule::enum(Plan::class)]])['plan']);

        $markPaid->handle($wedding, $plan);

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$wedding->couple_names}: {$plan->label()} freigeschaltet."]);

        return back();
    }
}
