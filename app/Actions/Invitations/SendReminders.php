<?php

namespace App\Actions\Invitations;

use App\Enums\WeddingStatus;
use App\Mail\GuestReminder;
use App\Models\Household;
use App\Models\Wedding;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

/**
 * Reminders 14, 7 and 2 days before the deadline (roadmap 1.10), to
 * households that have an address, haven't answered and haven't opted out.
 * Each stage at most once per household; never a burst of stages.
 */
class SendReminders
{
    /** @var list<int> */
    public const array STAGES = [14, 7, 2];

    /**
     * @return int Reminders queued.
     */
    public function handle(CarbonImmutable $today): int
    {
        $sent = 0;

        $weddings = Wedding::query()
            ->where('status', WeddingStatus::Active)
            ->where('sends_reminders', true)
            ->whereDate('rsvp_deadline', '>=', $today->toDateString())
            ->get();

        foreach ($weddings as $wedding) {
            $deadline = CarbonImmutable::parse((string) $wedding->rsvp_deadline?->toDateString(), $today->getTimezone());
            $stage = $this->dueStage((int) round($today->startOfDay()->diffInDays($deadline)));

            if ($stage !== null) {
                $sent += $this->remind($wedding, $stage);
            }
        }

        return $sent;
    }

    /**
     * The stage due on the given day: its own day, or the day after it.
     */
    public function dueStage(int $daysLeft): ?int
    {
        foreach (self::STAGES as $stage) {
            if ($daysLeft === $stage || $daysLeft === $stage - 1) {
                return $stage;
            }
        }

        return null;
    }

    private function remind(Wedding $wedding, int $stage): int
    {
        $households = $wedding->households()
            ->whereNotNull('email')
            ->whereNull('responded_at')
            ->whereNull('reminders_opted_out_at')
            ->where(fn (Builder $query) => $query->whereNull('reminder_stage')->orWhere('reminder_stage', '>', $stage))
            ->get();

        $households->each(function (Household $household) use ($stage): void {
            Mail::to((string) $household->email)
                ->locale($household->locale->value)
                ->queue(new GuestReminder($household));

            $household->forceFill(['reminder_stage' => $stage])->save();
        });

        return $households->count();
    }
}
