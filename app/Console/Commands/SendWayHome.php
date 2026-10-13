<?php

namespace App\Console\Commands;

use App\Actions\Evening\SendWayHomeReminders;
use Carbon\CarbonImmutable;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('hereby:send-way-home')]
#[Description('Mail attending guests half an hour before the night\'s last connections')]
class SendWayHome extends Command
{
    public function handle(SendWayHomeReminders $reminders): int
    {
        $count = $reminders->handle(CarbonImmutable::now());
        $this->info("{$count} way-home mails queued.");

        return self::SUCCESS;
    }
}
