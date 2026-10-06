<?php

use App\Models\Wedding;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Delete guest data after the retention window (ADR 0007).
Schedule::command('model:prune', ['--model' => [Wedding::class]])->dailyAt('03:17');
