<?php

use App\Http\Controllers\Admin\WeddingsController;
use Illuminate\Support\Facades\Route;

/*
| The Hereby team (roadmap 1.1). Admins open any wedding on the couple's own
| pages; this area only lists them and sets new ones up.
*/
Route::middleware(['auth', 'verified', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('hochzeiten', [WeddingsController::class, 'index'])->name('weddings.index');
    Route::post('hochzeiten', [WeddingsController::class, 'store'])->name('weddings.store');
});
