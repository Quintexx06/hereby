<?php

namespace App\Enums;

use Illuminate\Http\Request;

/**
 * An admin works either as the team (every wedding, inquiries, partners,
 * the log) or as a couple on their own wedding. The choice lives in the
 * session; the team view is the default.
 */
enum AdminView: string
{
    case Team = 'team';
    case Couple = 'couple';

    public const SESSION_KEY = 'admin_view';

    /**
     * The admin's current view, or null for everyone who is not an admin.
     */
    public static function for(Request $request): ?self
    {
        if (! $request->user()?->is_admin) {
            return null;
        }

        return self::tryFrom((string) $request->session()->get(self::SESSION_KEY)) ?? self::Team;
    }
}
