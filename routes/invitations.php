<?php

use App\Http\Controllers\Invitations\DownloadCalendarController;
use App\Http\Controllers\Invitations\SaveReplyController;
use App\Http\Controllers\Invitations\ShowInvitationController;
use App\Http\Controllers\Invitations\ShowReplyController;
use App\Http\Controllers\Invitations\StopRemindersController;
use App\Http\Controllers\Memories\GuestUploadController;
use App\Http\Middleware\EnsureWeddingHasMemories;
use App\Http\Middleware\EnsureWeddingIsPublished;
use App\Http\Middleware\PreventIndexing;
use App\Http\Middleware\UseHouseholdLocale;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;

/*
| Guest-facing personal links. Public by design: the unguessable token is the
| credential (ADR 0005). Throttled, kept out of search engines, and always in
| the household's own language. Open once the couple has paid for a plan.
*/
Route::middleware(['throttle:invitations', PreventIndexing::class, EnsureWeddingIsPublished::class, UseHouseholdLocale::class])->group(function () {
    Route::get('i/{household}', ShowInvitationController::class)->name('invitation.show');
    Route::get('i/{household}/antwort', ShowReplyController::class)->name('invitation.reply');
    Route::put('i/{household}/antwort', SaveReplyController::class)->name('invitation.reply.update');
    Route::get('i/{household}/kalender.ics', DownloadCalendarController::class)->name('invitation.calendar');
    Route::match(['get', 'post'], 'i/{household}/keine-erinnerungen', StopRemindersController::class)
        ->middleware('signed')
        ->withoutMiddleware(PreventRequestForgery::class)
        ->name('invitation.reminders.stop');
});

/*
| «Fotos von allen»: one upload link per wedding, for every guest. The token
| is the credential, as on the personal links; open only with Atelier and up.
*/
Route::middleware([PreventIndexing::class, EnsureWeddingHasMemories::class])->group(function () {
    Route::get('fotos/{wedding:memories_token}', [GuestUploadController::class, 'show'])
        ->middleware('throttle:invitations')
        ->name('invitation.memories.show');
    Route::post('fotos/{wedding:memories_token}', [GuestUploadController::class, 'store'])
        ->middleware('throttle:photo-uploads')
        ->name('invitation.memories.store');
});
