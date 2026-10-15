<?php

namespace App\Http\Controllers\Partners;

use App\Actions\Partners\ConnectPartner;
use App\Actions\Partners\SetUpPartner;
use App\Concerns\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerInvite;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * The link in a couple's invitation: the partner chooses a password and
 * lands in their own area, the couple's wedding already connected.
 */
class PartnerJoinController extends Controller
{
    use PasswordValidationRules;

    public function show(string $token): Response
    {
        $invite = $this->invite($token);

        return Inertia::render('auth/PartnerJoin', [
            'token' => $invite->token,
            'couple' => $invite->wedding->couple_names,
            'name' => $invite->name,
            'email' => $invite->email,
            'typeLabel' => $invite->type->label(),
            'passwordRules' => Password::defaults()->toPasswordRulesString(),
        ]);
    }

    public function store(Request $request, string $token, SetUpPartner $setUp, ConnectPartner $connect): RedirectResponse
    {
        $invite = $this->invite($token);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'password' => $this->passwordRules(),
        ]);

        if (User::query()->where('email', $invite->email)->exists()) {
            throw ValidationException::withMessages(['name' => 'Mit dieser Adresse gibt es schon einen Zugang. Meldet euch einfach an.']);
        }

        $user = DB::transaction(function () use ($invite, $data, $setUp, $connect): User {
            $user = $setUp->open(['type' => $invite->type->value, 'name' => $data['name'], 'email' => $invite->email], $data['password']);
            /** @var Partner $partner */
            $partner = $user->partner;
            $connect->handle($invite->wedding, $partner);
            $invite->delete();

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        Inertia::flash('toast', ['type' => 'success', 'message' => "Willkommen. {$invite->wedding->couple_names} sind verbunden."]);

        return to_route('partner.calendar');
    }

    private function invite(string $token): PartnerInvite
    {
        return PartnerInvite::query()->valid()->where('token', $token)->with('wedding')->firstOrFail();
    }
}
