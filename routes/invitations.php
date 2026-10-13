<?php

use App\Http\Controllers\Invitations\DownloadCalendarController;
use App\Http\Controllers\Invitations\NewspaperController;
use App\Http\Controllers\Invitations\ProgrammeController;
use App\Http\Controllers\Invitations\SaveReplyController;
use App\Http\Controllers\Invitations\ShowInvitationController;
use App\Http\Controllers\Invitations\ShowReplyController;
use App\Http\Controllers\Invitations\StopRemindersController;
use App\Http\Controllers\Memories\GuestUploadController;
use App\Http\Controllers\Witness\GroupPhotoCallController;
use App\Http\Controllers\Witness\NewspaperController as WitnessNewspaperController;
use App\Http\Controllers\Witness\ProgrammeController as WitnessProgrammeController;
use App\Http\Controllers\Witness\WitnessController;
use App\Http\Middleware\EnsureWeddingHasEvening;
use App\Http\Middleware\EnsureWeddingHasMemories;
use App\Http\Middleware\EnsureWeddingHasNewspaper;
use App\Http\Middleware\EnsureWeddingIsPublished;
use App\Http\Middleware\EnsureWitnessLinkIsOpen;
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

    // «Etwas vorbereiten?»: offers for the evening (Atelier and up, spec 2026-10-13).
    Route::middleware(EnsureWeddingHasEvening::class)->scopeBindings()->group(function () {
        Route::get('i/{household}/einlage', [ProgrammeController::class, 'show'])->name('invitation.programme');
        Route::post('i/{household}/einlage', [ProgrammeController::class, 'store'])->name('invitation.programme.store');
        Route::delete('i/{household}/einlage/{programmeItem}', [ProgrammeController::class, 'destroy'])
            ->name('invitation.programme.destroy');
    });
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

/*
| The witnesses' private link (spec 2026-10-13): the couple shares it, the
| token is the credential. The evening's offers, decided and ordered there.
*/
Route::middleware(['throttle:invitations', PreventIndexing::class, EnsureWitnessLinkIsOpen::class])
    ->scopeBindings()
    ->group(function () {
        Route::get('trauzeugen/{wedding:witness_token}', [WitnessController::class, 'show'])->name('witness.show');

        Route::middleware(EnsureWeddingHasEvening::class)->group(function () {
            Route::patch('trauzeugen/{wedding:witness_token}/einlagen/{programmeItem}', [WitnessProgrammeController::class, 'update'])
                ->name('witness.programme.update');
            Route::put('trauzeugen/{wedding:witness_token}/einlagen', [WitnessProgrammeController::class, 'reorder'])
                ->name('witness.programme.reorder');
            Route::get('trauzeugen/{wedding:witness_token}/ablauf', [WitnessProgrammeController::class, 'print'])
                ->name('witness.programme.print');
            Route::post('trauzeugen/{wedding:witness_token}/gruppenfotos/{groupPhoto}/aufruf', GroupPhotoCallController::class)
                ->name('witness.group-photos.call');
        });

        Route::middleware(EnsureWeddingHasNewspaper::class)->group(function () {
            Route::get('trauzeugen/{wedding:witness_token}/zeitung', [WitnessNewspaperController::class, 'print'])
                ->name('witness.newspaper.print');
            Route::get('trauzeugen/{wedding:witness_token}/zeitung/{newspaperArticle}/foto', [WitnessNewspaperController::class, 'photo'])
                ->name('witness.newspaper.photo');
            Route::delete('trauzeugen/{wedding:witness_token}/zeitung/{newspaperArticle}', [WitnessNewspaperController::class, 'destroy'])
                ->name('witness.newspaper.destroy');
        });
    });

/*
| «Hochzeitszeitung»: one contribution link per wedding, for friends. The
| token is the credential; open with the newspaper extra or Couture.
*/
Route::middleware(['throttle:invitations', PreventIndexing::class, EnsureWeddingHasNewspaper::class])->group(function () {
    Route::get('zeitung/{wedding:newspaper_token}', [NewspaperController::class, 'show'])->name('invitation.newspaper.show');
    Route::post('zeitung/{wedding:newspaper_token}', [NewspaperController::class, 'store'])
        ->middleware('throttle:photo-uploads')
        ->name('invitation.newspaper.store');
});
