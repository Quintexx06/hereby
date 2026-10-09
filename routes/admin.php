<?php

use App\Http\Controllers\Admin\InquiriesController;
use App\Http\Controllers\Admin\WeddingsController;
use Illuminate\Support\Facades\Route;

/*
| The Hereby team (roadmap 1.1). Admins open any wedding on the couple's own
| pages; this area lists them, sets new ones up and answers landing questions.
*/
Route::middleware(['auth', 'verified', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('hochzeiten', [WeddingsController::class, 'index'])->name('weddings.index');
    Route::post('hochzeiten', [WeddingsController::class, 'store'])->name('weddings.store');
    Route::get('anfragen', [InquiriesController::class, 'index'])->name('inquiries.index');
    Route::patch('anfragen/{inquiry}', [InquiriesController::class, 'update'])->name('inquiries.update');
});
