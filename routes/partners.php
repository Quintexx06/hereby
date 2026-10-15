<?php

use App\Http\Controllers\Partners\CalendarController;
use App\Http\Controllers\Partners\FinalNumbersController;
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
    Route::get('vorschau', TourPreviewController::class)->name('preview');
    Route::get('hochzeiten/{wedding}/blatt', FinalNumbersController::class)->name('sheet');
    Route::get('hochzeiten/{wedding}/fotos', [PhotoUploadController::class, 'show'])->name('photos.show');
    Route::post('hochzeiten/{wedding}/fotos', [PhotoUploadController::class, 'store'])
        ->middleware('throttle:photo-uploads')
        ->name('photos.store');
});
