<?php

use App\Http\Controllers\Invitations\ShowInvitationController;
use App\Http\Middleware\PreventIndexing;
use Illuminate\Support\Facades\Route;

/*
| Guest-facing personal links. Public by design: the unguessable token is the
| credential (ADR 0005). Throttled and kept out of search engines.
*/
Route::middleware(['throttle:invitations', PreventIndexing::class])->group(function () {
    Route::get('i/{household}', ShowInvitationController::class)->name('invitation.show');
});
