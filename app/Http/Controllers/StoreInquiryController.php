<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Mail\InquiryReceived;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

/**
 * "Fragt uns": a couple's question from the landing page FAQ. Stored, and
 * mailed to the team inbox (config hereby.inbox) when one is configured.
 */
class StoreInquiryController extends Controller
{
    public function __invoke(StoreInquiryRequest $request): RedirectResponse
    {
        $inquiry = Inquiry::create($request->safe()->only(['email', 'question']));

        if ($inbox = config('hereby.inbox')) {
            Mail::to($inbox)->queue(new InquiryReceived($inquiry));
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Danke! Wir antworten euch so bald wie möglich.']);

        return back();
    }
}
