<?php

namespace App\Actions\Evening;

use App\Enums\ResponseStatus;
use App\Mail\WayHomeReminder;
use App\Models\Household;
use App\Models\Wedding;
use App\Support\WayHome;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

/**
 * Half an hour before each of the night's last connections, one mail to
 * every attending household with an address (spec 2026-10-13). Each
 * connection is announced once, however often this runs.
 */
class SendWayHomeReminders
{
    /** How long before departure the mail goes out, in minutes. */
    public const int LEAD_MINUTES = 35;

    /**
     * @return int Mails queued.
     */
    public function handle(CarbonImmutable $now): int
    {
        $today = $now->setTimezone(WayHome::TIME_ZONE);
        $sent = 0;

        $weddings = Wedding::query()
            ->whereNotNull('way_home')
            ->whereNotNull('paid_at')
            ->whereDate('wedding_date', '>=', $today->subDay()->toDateString())
            ->whereDate('wedding_date', '<=', $today->toDateString())
            ->get();

        foreach ($weddings->filter(fn (Wedding $wedding): bool => $wedding->includesEvening()) as $wedding) {
            foreach (WayHome::connections($wedding) as $connection) {
                $due = $now->greaterThanOrEqualTo($connection['departs']->subMinutes(self::LEAD_MINUTES))
                    && $now->lessThan($connection['departs']);

                if ($due && ! in_array($connection['key'], $wedding->way_home_sent ?? [], true)) {
                    $sent += $this->announce($wedding, $connection);
                }
            }
        }

        return $sent;
    }

    /**
     * @param  array{key: string, label: string, place: string|null, departs: CarbonImmutable}  $connection
     */
    private function announce(Wedding $wedding, array $connection): int
    {
        $wedding->forceFill(['way_home_sent' => [...($wedding->way_home_sent ?? []), $connection['key']]])->save();

        $households = $wedding->households()
            ->whereNotNull('email')
            ->whereHas('guests.responses', fn (Builder $query) => $query->where('status', ResponseStatus::Attending))
            ->get();

        $households->each(fn (Household $household) => Mail::to((string) $household->email)
            ->locale($household->locale->value)
            ->queue(new WayHomeReminder($household, [
                'label' => $connection['label'],
                'place' => $connection['place'],
                'time' => $connection['departs']->format('H:i'),
            ])));

        return $households->count();
    }
}
