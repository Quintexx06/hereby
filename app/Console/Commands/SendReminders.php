<?php

namespace App\Console\Commands;

use App\Actions\Invitations\SendReminders as SendRemindersAction;
use Carbon\CarbonImmutable;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('hereby:send-reminders')]
#[Description('Email unanswered households 14, 7 and 2 days before the RSVP deadline')]
class SendReminders extends Command
{
    public function handle(SendRemindersAction $reminders): int
    {
        $count = $reminders->handle(CarbonImmutable::now('Europe/Zurich')->startOfDay());
        $this->info("{$count} reminders queued.");

        return self::SUCCESS;
    }
}
