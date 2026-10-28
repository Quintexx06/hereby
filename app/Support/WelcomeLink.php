<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\URL;

/**
 * The signed link in a welcome mail (WelcomeLinkController): valid for seven
 * days, and only until a password is chosen, since it carries a stamp of the
 * password the account had when the mail went out.
 */
class WelcomeLink
{
    public const int DAYS = 7;

    public static function to(User $user): string
    {
        return URL::temporarySignedRoute('welcome-link', now()->addDays(self::DAYS), [
            'user' => $user,
            'v' => self::stamp($user),
        ]);
    }

    /**
     * Whether the link still opens: the password is the one it was sent for.
     * Links mailed before the stamp existed carry none and run out within the week.
     */
    public static function isCurrent(User $user, ?string $stamp): bool
    {
        return $stamp === null || hash_equals(self::stamp($user), $stamp);
    }

    private static function stamp(User $user): string
    {
        return substr(hash_hmac('sha256', (string) $user->password, config()->string('app.key')), 0, 16);
    }
}
