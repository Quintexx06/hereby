<?php

namespace App\Http\Controllers\Weddings;

use App\Actions\Partners\ConnectPartner;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerInvite;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Partner»: the couple connects their venue, photographer or planner with
 * the partner's code, and can disconnect them again at any time.
 */
class WeddingPartnersController extends Controller
{
    public function index(Request $request, Wedding $wedding): Response
    {
        $partners = $wedding->partners()->orderBy('name')->get();
        $connectedOn = DB::table('partner_wedding')->where('wedding_id', $wedding->id)->pluck('created_at', 'partner_id');
        $suggested = Partner::findByCode($request->string('code')->value());

        return Inertia::render('weddings/Partners', [
            'wedding' => ['id' => $wedding->id, 'plan' => $wedding->plan],
            'partners' => $partners->map(fn (Partner $partner): array => [
                'id' => $partner->id,
                'name' => $partner->name,
                'type' => $partner->type,
                'type_label' => $partner->type->label(),
                'town' => $partner->town,
                'since' => $connectedOn->has($partner->id) ? (substr((string) $connectedOn[$partner->id], 0, 10) ?: null) : null,
            ]),
            'invites' => $wedding->partnerInvites()->valid()->latest()->get()->map(fn (PartnerInvite $invite): array => [
                'id' => $invite->id,
                'type' => $invite->type,
                'name' => $invite->name,
                'email' => $invite->email,
            ]),
            'suggested' => $suggested && ! $partners->contains($suggested) ? [
                'name' => $suggested->name,
                'type_label' => $suggested->type->label(),
                'town' => $suggested->town,
                'code' => $suggested->code,
            ] : null,
        ]);
    }

    public function store(Request $request, Wedding $wedding, ConnectPartner $connect): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'max:20']]);

        $partner = Partner::findByCode($request->string('code')->value());

        if (! $partner) {
            throw ValidationException::withMessages(['code' => 'Diesen Code kennen wir nicht. Fragt euren Partner noch einmal.']);
        }

        $connect->handle($wedding, $partner);

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$partner->name} ist verbunden."]);

        return to_route('weddings.partners.index', $wedding);
    }

    public function destroy(Wedding $wedding, Partner $partner): RedirectResponse
    {
        $wedding->partners()->detach($partner->id);

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$partner->name} sieht eure Hochzeit nicht mehr."]);

        return back();
    }
}
