<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Partners\SetUpPartner;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VenueDemoController;
use App\Http\Requests\Admin\StorePartnerRequest;
use App\Http\Requests\Admin\UpdatePartnerRequest;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
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
                    'demo_url' => $partner->isVenue()
                        ? URL::temporarySignedRoute('venues.demo', now()->addDays(VenueDemoController::DAYS_VALID), ['partner' => $partner->slug])
                        : null,
                ]),
        ]);
    }

    public function store(StorePartnerRequest $request, SetUpPartner $setUp): RedirectResponse
    {
        /** @var array{type: string, name: string, email?: string|null, town?: string|null, without_login?: bool} $data */
        $data = $request->validated();
        $partner = $setUp->handle($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => $partner->users()->exists()
            ? "{$partner->name} angelegt. Die Einladung ist unterwegs."
            : "{$partner->name} als Interessent angelegt. Der Demo-Link steht in der Liste."]);

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
    public function resend(Partner $partner, SetUpPartner $setUp): RedirectResponse
    {
        $user = $partner->users()->first();

        if (! $user) {
            // A prospect said yes: its contact address becomes the sign-in.
            abort_if(blank($partner->email) || User::query()->where('email', $partner->email)->exists(), 422, 'Tragt zuerst eine freie E-Mail-Adresse für Paare ein.');
            $user = $setUp->invite($partner, (string) $partner->email);
        } else {
            $setUp->sendInvitation($user, $partner->name);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => "Zugangslink an {$user->email} unterwegs."]);

        return back();
    }
}
