<?php

namespace App\Console\Commands;

use App\Models\Household;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('hereby:demo-link')]
#[Description('Print the path of one guest link from the seeded demo wedding (performance checks)')]
class DemoLink extends Command
{
    public function handle(): int
    {
        $household = Household::query()->whereHas('events')->oldest('id')->first();

        if (! $household) {
            $this->error('No household with events. Run php artisan db:seed first.');

            return self::FAILURE;
        }

        $this->line(route('invitation.show', $household, false));

        return self::SUCCESS;
    }
}
