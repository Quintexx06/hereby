<?php

namespace App\Http\Controllers\Weddings;

use App\Actions\Partners\ConnectPartner;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * «Partner»: the couple connects their venue, photographer or planner with
 * the partner's code, and can disconnect them again at any time.
 */
class WeddingPartnersController extends Controller
{
    public function index(Wedding $wedding): Response
    {
        return Inertia::render('weddings/Partners', [
            'wedding' => ['id' => $wedding->id, 'plan' => $wedding->plan],
            'partners' => $wedding->partners()->orderBy('name')->get()->map(fn (Partner $partner): array => [
                'id' => $partner->id,
                'name' => $partner->name,
                'type' => $partner->type,
                'type_label' => $partner->type->label(),
                'town' => $partner->town,
                'sees_numbers' => $partner->type->seesFinalNumbers(),
            ]),
        ]);
    }

    public function store(Request $request, Wedding $wedding, ConnectPartner $connect): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'max:20']]);

        $code = Str::upper(preg_replace('/[^A-Za-z0-9]/', '', $request->string('code')->value()) ?? '');
        $partner = Partner::query()->where('code', substr($code, 0, 4).'-'.substr($code, 4))->first();

        if (! $partner) {
            throw ValidationException::withMessages(['code' => 'Diesen Code kennen wir nicht. Fragt euren Partner noch einmal.']);
        }

        $connect->handle($wedding, $partner);

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$partner->name} ist verbunden."]);

        return back();
    }

    public function destroy(Wedding $wedding, Partner $partner): RedirectResponse
    {
        $wedding->partners()->detach($partner->id);

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$partner->name} sieht eure Hochzeit nicht mehr."]);

        return back();
    }
}
