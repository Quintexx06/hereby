<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StoreInquiryController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\UseSwissGerman;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('home');
Route::post('fragen', StoreInquiryController::class)->middleware('throttle:inquiries')->name('inquiries.store');

Route::middleware(UseSwissGerman::class)->group(function () {
    Route::inertia('datenschutz', 'legal/Privacy')->name('legal.privacy');
    Route::inertia('impressum', 'legal/Imprint')->name('legal.imprint');
});

/*
| The couple's side speaks Swiss German, so validation, flash and relative
| dates match the page. Guest links set the household's own locale.
*/
Route::middleware(UseSwissGerman::class)->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
    });

    require __DIR__.'/settings.php';
    require __DIR__.'/weddings.php';
    require __DIR__.'/admin.php';
});

require __DIR__.'/invitations.php';
