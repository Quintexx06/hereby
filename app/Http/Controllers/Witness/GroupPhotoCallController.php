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
    public function __invoke(Wedding $wedding, GroupPhoto $groupPhoto): RedirectResponse
    {
        $groupPhoto->forceFill(['called_at' => now()])->save();

        $groupPhoto->households()->whereNotNull('email')->get()
            ->each(fn (Household $household) => Mail::to((string) $household->email)
                ->locale($household->locale->value)
                ->queue(new GroupPhotoCall($household, $groupPhoto)));

        return back();
    }
}
