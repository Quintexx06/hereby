<?php

namespace App\Http\Controllers;

use App\Mail\VenueEnquiryReceived;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

/**
 * A Showcase venue's public page: where a guest lands from the one credit
 * line, and can ask for a tour (opt-in, spec 2026-10-15-partner-accounts).
 */
class VenuePageController extends Controller
{
    public function show(Partner $partner): Response
    {
        abort_unless($partner->isVenue() && $partner->is_showcase, 404);

        return Inertia::render('venues/Show', [
            'venue' => [
                'slug' => $partner->slug,
                'name' => $partner->name,
                'town' => trim("{$partner->postcode} {$partner->town}") ?: null,
                'address' => $partner->address,
                'capacity' => $partner->capacity,
                'about' => $partner->about,
                'travel' => $partner->travel,
                'stay' => $partner->stay,
                'website' => $partner->website,
            ],
        ]);
    }

    public function store(Request $request, Partner $partner): RedirectResponse
    {
        abort_unless($partner->isVenue() && $partner->is_showcase, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:191'],
            'preferred_date' => ['nullable', 'date', 'after:today'],
            'message' => ['nullable', 'string', 'max:2000'],
        ], [
            'name.required' => 'Wie heisst ihr?',
            'email.required' => 'Wohin dürfen wir antworten?',
            'preferred_date.after' => 'Das Datum sollte in der Zukunft liegen.',
        ]);

        $enquiry = $partner->enquiries()->create($data);

        if ($partner->email) {
            Mail::to($partner->email)->queue(new VenueEnquiryReceived($enquiry));
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => "Danke! {$partner->name} meldet sich bei euch."]);

        return back();
    }
}
