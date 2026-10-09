<?php

use App\Http\Controllers\Invitations\DownloadCalendarController;
use App\Http\Controllers\Invitations\SaveReplyController;
use App\Http\Controllers\Invitations\ShowInvitationController;
use App\Http\Controllers\Invitations\ShowReplyController;
use App\Http\Middleware\PreventIndexing;
use App\Http\Middleware\UseHouseholdLocale;
use Illuminate\Support\Facades\Route;

/*
| Guest-facing personal links. Public by design: the unguessable token is the
| credential (ADR 0005). Throttled, kept out of search engines, and always in
| the household's own language.
*/
Route::middleware(['throttle:invitations', PreventIndexing::class, UseHouseholdLocale::class])->group(function () {
    Route::get('i/{household}', ShowInvitationController::class)->name('invitation.show');
    Route::get('i/{household}/antwort', ShowReplyController::class)->name('invitation.reply');
    Route::put('i/{household}/antwort', SaveReplyController::class)->name('invitation.reply.update');
    Route::get('i/{household}/kalender.ics', DownloadCalendarController::class)->name('invitation.calendar');
});
