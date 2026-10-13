<?php

namespace App\Console\Commands;

use App\Actions\Weddings\StripGuestData as StripGuestDataAction;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('hereby:strip-guest-data')]
#[Description('Delete guest data of weddings past retention whose memories are kept (ADR 0011)')]
class StripGuestData extends Command
{
    public function handle(StripGuestDataAction $strip): int
    {
        $count = $strip->handle();
        $this->info("Guest data deleted for {$count} weddings.");

        return self::SUCCESS;
    }
}
