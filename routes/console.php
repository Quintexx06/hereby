<?php

use App\Models\VenueEnquiry;
use App\Models\Wedding;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Delete guest data after the retention window (ADR 0007); weddings with
// kept memories lose their guest data first and the rest later (ADR 0011).
Schedule::command('hereby:strip-guest-data')->dailyAt('03:07');
Schedule::command('model:prune', ['--model' => [Wedding::class, VenueEnquiry::class]])->dailyAt('03:17');

// Reminders 14, 7 and 2 days before each RSVP deadline (roadmap 1.10).
Schedule::command('hereby:send-reminders')->dailyAt('10:07')->timezone('Europe/Zurich');

// The night's last connections, half an hour ahead (spec 2026-10-13).
Schedule::command('hereby:send-way-home')->everyFiveMinutes()->withoutOverlapping();
