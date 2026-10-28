<?php

namespace App\Http\Responses;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Responses\PasswordResetResponse as FortifyPasswordResetResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * After a reset, the login form, as Fortify does. A first password chosen
 * from a welcome mail signs the couple or partner straight in instead: the
 * token from their inbox has just proved who they are.
 */
class PasswordResetResponse extends FortifyPasswordResetResponse
{
    /**
     * @param  Request  $request
     */
    public function toResponse($request): Response
    {
        $user = $request->boolean('welcome')
            ? User::query()->where('email', $request->string(Fortify::email())->value())->first()
            : null;

        if ($user === null || $user->hasEnabledTwoFactorAuthentication() || $request->wantsJson()) {
            return parent::toResponse($request);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
