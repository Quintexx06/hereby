<?php

use App\Http\Controllers\Admin\BriefsController;
use App\Http\Controllers\Admin\ChaseController;
use App\Http\Controllers\Admin\ConnectDomainController;
use App\Http\Controllers\Admin\InquiriesController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\PartnersController;
use App\Http\Controllers\Admin\SwitchViewController;
use App\Http\Controllers\Admin\UnlockWeddingController;
use App\Http\Controllers\Admin\WeddingsController;
use Illuminate\Support\Facades\Route;

/*
| The Hereby team (roadmap 1.1). Admins open any wedding on the couple's own
| pages; this area lists them, sets new ones up and answers landing questions.
*/
Route::middleware(['auth', 'verified', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('ansicht', SwitchViewController::class)->name('view');
    Route::get('protokoll', [LogController::class, 'index'])->name('log.index');
    Route::patch('protokoll/{logEntry}', [LogController::class, 'update'])->name('log.update');
    Route::delete('protokoll', [LogController::class, 'destroy'])->name('log.destroy');
    Route::get('hochzeiten', [WeddingsController::class, 'index'])->name('weddings.index');
    Route::post('hochzeiten', [WeddingsController::class, 'store'])->name('weddings.store');
    Route::patch('hochzeiten/{wedding}/freischalten', UnlockWeddingController::class)->name('weddings.unlock');
    Route::patch('hochzeiten/{wedding}/domain', ConnectDomainController::class)->name('weddings.domain');
    Route::get('anfragen', [InquiriesController::class, 'index'])->name('inquiries.index');
    Route::patch('anfragen/{inquiry}', [InquiriesController::class, 'update'])->name('inquiries.update');
    Route::get('massanfertigung', [BriefsController::class, 'index'])->name('briefs.index');
    Route::patch('massanfertigung/{brief}', [BriefsController::class, 'update'])->name('briefs.update');
    Route::get('partner', [PartnersController::class, 'index'])->name('partners.index');
    Route::post('partner', [PartnersController::class, 'store'])->name('partners.store');
    Route::post('partner/liste', [PartnersController::class, 'import'])->name('partners.import');
    Route::patch('partner/{partner}', [PartnersController::class, 'update'])->name('partners.update');
    Route::post('partner/{partner}/zugang', [PartnersController::class, 'resend'])->name('partners.resend');
    Route::get('nachfassen', [ChaseController::class, 'index'])->name('chase.index');
    Route::patch('nachfassen/{household:id}', [ChaseController::class, 'update'])->name('chase.update');
});
