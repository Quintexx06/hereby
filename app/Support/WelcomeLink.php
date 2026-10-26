<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\URL;

/**
 * The signed link in a welcome mail, valid for seven days (WelcomeLinkController).
 */
class WelcomeLink
{
    public const int DAYS = 7;

    public static function to(User $user): string
    {
        return URL::temporarySignedRoute('welcome-link', now()->addDays(self::DAYS), ['user' => $user]);
    }
}
