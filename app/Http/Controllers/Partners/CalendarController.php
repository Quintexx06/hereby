<?php

namespace App\Http\Controllers\Partners;

use App\Actions\Partners\BuildPartnerCalendar;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\VenueEnquiry;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The partner's start page: their code for couples, the connected weddings
 * by date, and, for Showcase venues, tour requests from the public page.
 */
class CalendarController extends Controller
{
    public function __invoke(Request $request, BuildPartnerCalendar $calendar): Response
    {
        /** @var Partner $partner */
        $partner = $request->user()?->partner;

        return Inertia::render('partner/Calendar', [
            'partner' => [
                'name' => $partner->name,
                'type' => $partner->type,
                'type_label' => $partner->type->label(),
                'code' => $partner->code,
                'is_showcase' => $partner->is_showcase,
                'sees_numbers' => $partner->type->seesFinalNumbers(),
                'page' => $partner->is_showcase ? route('venues.show', $partner->slug) : null,
            ],
            'weddings' => $calendar->handle($partner),
            'enquiries' => $partner->is_showcase
                ? $partner->enquiries()->latest()->limit(30)->get()->map(fn (VenueEnquiry $enquiry): array => [
                    'id' => $enquiry->id,
                    'name' => $enquiry->name,
                    'email' => $enquiry->email,
                    'preferred_date' => $enquiry->preferred_date?->toDateString(),
                    'message' => $enquiry->message,
                    'received' => $enquiry->created_at?->toDateString(),
                ])
                : [],
        ]);
    }
}
