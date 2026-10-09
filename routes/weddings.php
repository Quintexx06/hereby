<?php

use App\Http\Controllers\AddressSearchController;
use App\Http\Controllers\Guests\GuestsController;
use App\Http\Controllers\Guests\PreviewGuestImportController;
use App\Http\Controllers\Guests\StoreHouseholdsController;
use App\Http\Controllers\Weddings\CompleteWeddingSetupController;
use App\Http\Controllers\Weddings\StartWeddingSetupController;
use App\Http\Controllers\Weddings\WeddingSetupController;
use Illuminate\Support\Facades\Route;

/*
| The couple's side: setting up a wedding and running it. Every route acts on
| the signed-in couple's own wedding (WeddingPolicy).
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('hochzeit', StartWeddingSetupController::class)->name('weddings.store');

    Route::get('hochzeit/{wedding}/einrichten/{step}', [WeddingSetupController::class, 'show'])
        ->name('weddings.setup.show');
    Route::put('hochzeit/{wedding}/einrichten/{step}', [WeddingSetupController::class, 'update'])
        ->name('weddings.setup.update');
    Route::post('hochzeit/{wedding}/abschliessen', CompleteWeddingSetupController::class)
        ->name('weddings.setup.complete');

    Route::middleware('can:update,wedding')->group(function () {
        Route::get('hochzeit/{wedding}/gaeste', GuestsController::class)->name('weddings.guests.index');
        Route::post('hochzeit/{wedding}/gaeste', StoreHouseholdsController::class)->name('weddings.households.store');
        Route::post('hochzeit/{wedding}/gaeste/vorschau', PreviewGuestImportController::class)
            ->middleware('throttle:30,1')
            ->name('weddings.guests.preview');
    });

    Route::get('adressen', AddressSearchController::class)
        ->middleware('throttle:address-search')
        ->name('addresses.search');
});
