<?php

use App\Http\Controllers\AddressSearchController;
use App\Http\Controllers\Billing\PublishController;
use App\Http\Controllers\Content\ContentBlockController;
use App\Http\Controllers\Content\PreviewInvitationController;
use App\Http\Controllers\Content\ReorderContentBlocksController;
use App\Http\Controllers\Evening\EveningController;
use App\Http\Controllers\Evening\GroupPhotosController;
use App\Http\Controllers\Evening\ProgrammeController;
use App\Http\Controllers\Evening\SeatingController;
use App\Http\Controllers\Evening\WayHomeController;
use App\Http\Controllers\Extras\ExtrasController;
use App\Http\Controllers\Extras\NewspaperController;
use App\Http\Controllers\Guests\GuestsController;
use App\Http\Controllers\Guests\HouseholdController;
use App\Http\Controllers\Guests\PreviewGuestImportController;
use App\Http\Controllers\Guests\StoreHouseholdsController;
use App\Http\Controllers\Kitchen\KitchenController;
use App\Http\Controllers\Memories\MemoriesController;
use App\Http\Controllers\Weddings\CompleteWeddingSetupController;
use App\Http\Controllers\Weddings\RsvpSettingsController;
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

        Route::get('hochzeit/{wedding}/antwortformular', [RsvpSettingsController::class, 'edit'])
            ->name('weddings.rsvp-settings.edit');
        Route::put('hochzeit/{wedding}/antwortformular', [RsvpSettingsController::class, 'update'])
            ->name('weddings.rsvp-settings.update');

        Route::get('hochzeit/{wedding}/inhalte', [ContentBlockController::class, 'index'])->name('weddings.content.index');
        Route::post('hochzeit/{wedding}/inhalte', [ContentBlockController::class, 'store'])->name('weddings.content.store');
        Route::put('hochzeit/{wedding}/inhalte/reihenfolge', ReorderContentBlocksController::class)->name('weddings.content.reorder');
        Route::get('hochzeit/{wedding}/vorschau', PreviewInvitationController::class)->name('weddings.preview');

        Route::get('hochzeit/{wedding}/freischalten', [PublishController::class, 'show'])->name('weddings.publish.show');
        Route::post('hochzeit/{wedding}/freischalten', [PublishController::class, 'store'])->name('weddings.publish.store');
        Route::get('hochzeit/{wedding}/freischalten/zurueck', [PublishController::class, 'confirm'])->name('weddings.publish.return');

        Route::get('hochzeit/{wedding}/abend', [EveningController::class, 'show'])->name('weddings.evening.show');
        Route::get('hochzeit/{wedding}/tische', [SeatingController::class, 'edit'])->name('weddings.seating.edit');
        Route::put('hochzeit/{wedding}/tische', [SeatingController::class, 'update'])->name('weddings.seating.update');

        Route::get('hochzeit/{wedding}/heimweg', [WayHomeController::class, 'edit'])->name('weddings.way-home.edit');
        Route::put('hochzeit/{wedding}/heimweg', [WayHomeController::class, 'update'])->name('weddings.way-home.update');

        Route::get('hochzeit/{wedding}/einlagen', [ProgrammeController::class, 'edit'])->name('weddings.programme.edit');
        Route::put('hochzeit/{wedding}/einlagen', [ProgrammeController::class, 'update'])->name('weddings.programme.update');
        Route::post('hochzeit/{wedding}/einlagen/link', [ProgrammeController::class, 'renew'])->name('weddings.programme.renew');

        Route::get('hochzeit/{wedding}/gruppenfotos', [GroupPhotosController::class, 'edit'])->name('weddings.group-photos.edit');
        Route::put('hochzeit/{wedding}/gruppenfotos', [GroupPhotosController::class, 'update'])->name('weddings.group-photos.update');

        Route::get('hochzeit/{wedding}/zeitung', [NewspaperController::class, 'edit'])->name('weddings.newspaper.edit');

        Route::get('hochzeit/{wedding}/extras', [ExtrasController::class, 'show'])->name('weddings.extras.show');
        Route::post('hochzeit/{wedding}/extras', [ExtrasController::class, 'store'])->name('weddings.extras.store');
        Route::get('hochzeit/{wedding}/extras/zurueck', [ExtrasController::class, 'confirm'])->name('weddings.extras.return');

        Route::get('hochzeit/{wedding}/kueche', [KitchenController::class, 'index'])->name('weddings.kitchen.index');
        Route::get('hochzeit/{wedding}/kueche/blatt', [KitchenController::class, 'sheet'])->name('weddings.kitchen.sheet');
        Route::get('hochzeit/{wedding}/kueche/gaeste.csv', [KitchenController::class, 'csv'])->name('weddings.kitchen.csv');

        Route::get('hochzeit/{wedding}/erinnerungen', [MemoriesController::class, 'index'])->name('weddings.memories.index');

        Route::scopeBindings()->group(function () {
            Route::middleware('can:useMemories,wedding')->group(function () {
                Route::get('hochzeit/{wedding}/erinnerungen/{photo}/{size}', [MemoriesController::class, 'photo'])
                    ->whereIn('size', ['klein', 'original'])
                    ->name('weddings.memories.photo');
                Route::delete('hochzeit/{wedding}/erinnerungen/{photo}', [MemoriesController::class, 'destroy'])
                    ->name('weddings.memories.destroy');
            });
            Route::put('hochzeit/{wedding}/inhalte/{contentBlock:id}', [ContentBlockController::class, 'update'])->name('weddings.content.update');
            Route::delete('hochzeit/{wedding}/inhalte/{contentBlock:id}', [ContentBlockController::class, 'destroy'])->name('weddings.content.destroy');
            Route::put('hochzeit/{wedding}/haushalte/{household:id}', [HouseholdController::class, 'update'])
                ->name('weddings.households.update');
            Route::delete('hochzeit/{wedding}/haushalte/{household:id}', [HouseholdController::class, 'destroy'])
                ->name('weddings.households.destroy');
            Route::post('hochzeit/{wedding}/haushalte/{household:id}/link', [HouseholdController::class, 'renewLink'])
                ->name('weddings.households.renew-link');
        });
    });

    Route::get('adressen', AddressSearchController::class)
        ->middleware('throttle:address-search')
        ->name('addresses.search');
});
