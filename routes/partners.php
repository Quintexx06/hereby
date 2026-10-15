<?php

use App\Http\Controllers\Partners\CalendarController;
use App\Http\Controllers\Partners\ConnectLinkController;
use App\Http\Controllers\Partners\FinalNumbersController;
use App\Http\Controllers\Partners\PartnerJoinController;
use App\Http\Controllers\Partners\PartnerPhotoController;
use App\Http\Controllers\Partners\PhotoUploadController;
use App\Http\Controllers\Partners\ProfileController;
use App\Http\Controllers\Partners\TourPreviewController;
use App\Http\Middleware\EnsureUserIsPartner;
use Illuminate\Support\Facades\Route;

/*
| Venues, photographers and planners (spec 2026-10-15-partner-accounts).
| A `{wedding}` here is one whose couple connected the partner.
*/
Route::middleware(['auth', 'verified', EnsureUserIsPartner::class])->prefix('partner')->name('partner.')->group(function () {
    Route::get('/', CalendarController::class)->name('calendar');
    Route::get('profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profil/fotos', [PartnerPhotoController::class, 'store'])
        ->middleware('throttle:photo-uploads')
        ->name('profile.photos.store');
    Route::delete('profil/fotos/{partnerPhoto}', [PartnerPhotoController::class, 'destroy'])->name('profile.photos.destroy');
    Route::get('vorschau', TourPreviewController::class)->name('preview');
    Route::get('hochzeiten/{wedding}/blatt', FinalNumbersController::class)->name('sheet');
    Route::get('hochzeiten/{wedding}/fotos', [PhotoUploadController::class, 'show'])->name('photos.show');
    Route::post('hochzeiten/{wedding}/fotos', [PhotoUploadController::class, 'store'])
        ->middleware('throttle:photo-uploads')
        ->name('photos.store');
});

Route::get('ortsfoto/{partnerPhoto:token}', [PartnerPhotoController::class, 'show'])
    ->middleware('throttle:invitations')
    ->name('partner-photos.show');

// A couple's invitation by mail: the partner opens an already connected account.
Route::get('partner/beitreten/{token}', [PartnerJoinController::class, 'show'])
    ->middleware('throttle:invitations')
    ->name('partner-join.show');
Route::post('partner/beitreten/{token}', [PartnerJoinController::class, 'store'])
    ->middleware('throttle:invitations')
    ->name('partner-join.store');

// A partner's link for couples; login keeps it as the intended page.
Route::get('verbinden/{code}', ConnectLinkController::class)
    ->middleware(['auth', 'verified'])
    ->name('partners.connect');
