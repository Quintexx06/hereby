<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\WelcomeLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * The link in a welcome mail (a couple or partner the team set up): valid
 * for a week, it makes a fresh password token when opened and leads to
 * «Passwort wählen», so a link opened days later still works. Once a
 * password is chosen, it leads to the login instead.
 */
class WelcomeLinkController extends Controller
{
    public function __invoke(Request $request, User $user): RedirectResponse
    {
        $stamp = $request->query('v');

        if (! WelcomeLink::isCurrent($user, is_string($stamp) ? $stamp : null)) {
            return redirect()->route('login')->with('status', 'Euer Passwort ist schon gewählt. Meldet euch damit an.');
        }

        return redirect()->route('password.reset', [
            'token' => Password::createToken($user),
            'email' => $user->email,
            'willkommen' => 1,
        ]);
    }
}
