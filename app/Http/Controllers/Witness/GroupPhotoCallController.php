<?php

namespace App\Http\Controllers\Witness;

use App\Http\Controllers\Controller;
use App\Mail\GroupPhotoCall;
use App\Models\GroupPhoto;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

/**
 * On the day, a witness calls a group photo: its households get a mail and
 * a banner on their personal link for a few minutes (spec 2026-10-13).
 */
class GroupPhotoCallController extends Controller
{
    /** A second call within this many minutes only renews the banner, it sends no mail. */
    public const MAIL_COOLDOWN_MINUTES = 2;

    public function __invoke(Wedding $wedding, GroupPhoto $groupPhoto): RedirectResponse
    {
        $recentlyCalled = $groupPhoto->called_at?->gt(now()->subMinutes(self::MAIL_COOLDOWN_MINUTES)) ?? false;
        $groupPhoto->forceFill(['called_at' => now()])->save();

        if ($recentlyCalled) {
            return back();
        }

        $groupPhoto->households()->whereNotNull('email')->get()
            ->each(fn (Household $household) => Mail::to((string) $household->email)
                ->locale($household->locale->value)
                ->queue(new GroupPhotoCall($household, $groupPhoto)));

        return back();
    }
}
