<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Questions couples sent from the landing page FAQ. The team answers by
 * email and marks them answered here; open ones come first.
 */
class InquiriesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Inquiries', [
            'inquiries' => Inquiry::query()
                ->orderByRaw('answered_at is not null')
                ->latest()
                ->get()
                ->map(fn (Inquiry $inquiry): array => [
                    'id' => $inquiry->id,
                    'email' => $inquiry->email,
                    'question' => $inquiry->question,
                    'answered' => $inquiry->answered_at !== null,
                    'created_at' => $inquiry->created_at?->toIso8601String(),
                ]),
        ]);
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $answered = $request->validate(['answered' => ['required', 'boolean']])['answered'];

        $inquiry->forceFill(['answered_at' => $answered ? now() : null])->save();

        return back();
    }
}
