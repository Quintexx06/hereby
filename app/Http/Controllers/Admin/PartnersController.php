<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Partners\SetUpPartner;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePartnerRequest;
use App\Http\Requests\Admin\UpdatePartnerRequest;
use App\Mail\PartnerInvitation;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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
                    'email' => $partner->email,
                    'phone' => $partner->phone,
                    'website' => $partner->website,
                    'town' => $partner->town,
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
     * Corrects the partner's details; a new sign-in address moves the login.
     * Showcase (tour preview, public page, credit line) is for venues only.
     */
    public function update(UpdatePartnerRequest $request, Partner $partner): RedirectResponse
    {
        $data = $request->validated();
        $partner->fill(Arr::except($data, ['login', 'is_showcase']));

        if (array_key_exists('is_showcase', $data)) {
            $partner->is_showcase = (bool) $data['is_showcase'] && $partner->isVenue();
        }

        $partner->save();

        if (isset($data['login'])) {
            $partner->users()->update(['email' => Str::lower($data['login'])]);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => "{$partner->name} gespeichert."]);

        return back();
    }

    /**
     * Sends the sign-in a fresh link to choose a password.
     */
    public function resend(Partner $partner): RedirectResponse
    {
        $user = $partner->users()->firstOrFail();
        $link = route('password.reset', ['token' => Password::createToken($user), 'email' => $user->email]);
        Mail::to($user->email)->queue(new PartnerInvitation($partner->name, $link));

        Inertia::flash('toast', ['type' => 'success', 'message' => "Neuer Link an {$user->email} unterwegs."]);

        return back();
    }
}
