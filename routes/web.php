<?php

use App\Http\Controllers\Billing\StripeWebhookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Marketing\SitemapController;
use App\Http\Controllers\Marketing\TopicPageController;
use App\Http\Controllers\StoreInquiryController;
use App\Http\Controllers\VenueDemoController;
use App\Http\Controllers\VenuePageController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\PreventIndexing;
use App\Http\Middleware\UseSwissGerman;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('home');
Route::post('stripe/webhook', StripeWebhookController::class)
    ->withoutMiddleware(PreventRequestForgery::class)
    ->name('stripe.webhook');
Route::post('fragen', StoreInquiryController::class)->middleware('throttle:inquiries')->name('inquiries.store');
Route::get('sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::middleware(UseSwissGerman::class)->group(function () {
    Route::inertia('datenschutz', 'legal/Privacy')->name('legal.privacy');
    Route::inertia('impressum', 'legal/Imprint')->name('legal.imprint');
    Route::get('orte/{partner:slug}', [VenuePageController::class, 'show'])->name('venues.show');
    // A signed sales demo for a venue we want to win (no account, 90 days).
    Route::get('demo/{partner:slug}', VenueDemoController::class)
        ->middleware(['throttle:invitations', PreventIndexing::class])
        ->name('venues.demo');
    Route::get('{slug}', TopicPageController::class)
        ->whereIn('slug', TopicPageController::slugs())
        ->name('topics.show');
    Route::post('orte/{partner:slug}/anfrage', [VenuePageController::class, 'store'])
        ->middleware('throttle:inquiries')
        ->name('venues.enquiry');
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
    require __DIR__.'/partners.php';
});

require __DIR__.'/invitations.php';
