<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Partners\ImportProspects;
use App\Actions\Partners\SetUpPartner;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportProspectsRequest;
use App\Http\Requests\Admin\StorePartnerRequest;
use App\Http\Requests\Admin\UpdatePartnerRequest;
use App\Models\Partner;
use App\Models\User;
use App\Support\VenueDemoLinks;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
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
                    'address' => $partner->address,
                    'postcode' => $partner->postcode,
                    'town' => $partner->town,
                    'pitch_note' => $partner->pitch_note,
                    'demo_url' => $partner->isVenue()
                        ? VenueDemoLinks::fresh($partner)
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
     * Adds a pasted list of prospect venues at once, for the pitch.
     */
    public function import(ImportProspectsRequest $request, ImportProspects $import): RedirectResponse
    {
        $result = $import->handle($request->string('lines')->toString());
        $message = collect([
            $result['added'] === 1 ? '1 Interessent angelegt' : "{$result['added']} Interessenten angelegt",
            $result['skipped'] ? "{$result['skipped']} schon in der Liste" : null,
            $result['invalid'] ? 'Zeile '.implode(', ', $result['invalid']).' nicht lesbar' : null,
        ])->filter()->implode(', ');

        Inertia::flash('toast', ['type' => $result['invalid'] ? 'error' : 'success', 'message' => "{$message}."]);

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
            if (blank($partner->email) || User::query()->where('email', $partner->email)->exists()) {
                Inertia::flash('toast', ['type' => 'error', 'message' => 'Tragt zuerst eine E-Mail-Adresse ein, die noch kein Konto hat.']);

                return back();
            }

            $user = $setUp->invite($partner, (string) $partner->email);
        } else {
            $setUp->sendInvitation($user, $partner->name);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => "Zugangslink an {$user->email} unterwegs."]);

        return back();
    }

    /**
     * Removes a prospect that wants no contact, as the privacy policy
     * promises. Partners with a sign-in or a wedding stay.
     */
    public function destroy(Partner $partner): RedirectResponse
    {
        if ($partner->users()->exists() || $partner->weddings()->exists()) {
            Inertia::flash('toast', ['type' => 'error', 'message' => "{$partner->name} hat einen Zugang oder Hochzeiten und bleibt."]);

            return back();
        }

        $partner->delete();
        Inertia::flash('toast', ['type' => 'success', 'message' => "{$partner->name} gelöscht."]);

        return back();
    }
}
