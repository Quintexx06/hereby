<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

/**
 * The link in a welcome mail (a couple or partner the team set up): valid
 * for a week, it makes a fresh password token when opened and leads to
 * «Passwort wählen», so a link opened days later still works.
 */
class WelcomeLinkController extends Controller
{
    public function __invoke(User $user): RedirectResponse
    {
        return redirect()->route('password.reset', [
            'token' => Password::createToken($user),
            'email' => $user->email,
            'willkommen' => 1,
        ]);
    }
}
