<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Partners\SetUpPartner;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePartnerRequest;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Partner»: the team opens venue, photographer and planner accounts and
 * marks venues as Showcase (spec 2026-10-15-partner-accounts).
 */
class PartnersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Partners', [
            'partners' => Partner::query()
                ->with('users:id,partner_id,email')
                ->withCount('weddings')
                ->orderBy('name')
                ->get()
                ->map(fn (Partner $partner): array => [
                    'id' => $partner->id,
                    'type' => $partner->type,
                    'type_label' => $partner->type->label(),
                    'name' => $partner->name,
                    'code' => $partner->code,
                    'login' => $partner->users->first()?->email,
                    'weddings' => $partner->weddings_count,
                    'is_showcase' => $partner->is_showcase,
                ]),
        ]);
    }

    public function store(StorePartnerRequest $request, SetUpPartner $setUp): RedirectResponse
    {
        /** @var array{type: string, name: string, email: string} $data */
        $data = $request->validated();
        $partner = $setUp->handle($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$partner->name} angelegt. Die Einladung ist unterwegs."]);

        return back();
    }

    /**
     * Venue Showcase on or off: tour preview, public page and credit line.
     */
    public function update(Request $request, Partner $partner): RedirectResponse
    {
        abort_unless($partner->isVenue(), 422);

        $partner->is_showcase = $request->boolean('is_showcase');
        $partner->save();

        return back();
    }
}
