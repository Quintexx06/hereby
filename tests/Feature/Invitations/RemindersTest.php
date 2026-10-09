<?php

namespace Tests\Feature\Invitations;

use App\Enums\Locale;
use App\Mail\GuestReminder;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RemindersTest extends TestCase
{
    use RefreshDatabase;

    private Wedding $wedding;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->travelTo(now('Europe/Zurich')->setTime(10, 7));
        $this->wedding = Wedding::factory()->create([
            'couple_names' => 'Anna & Luca',
            'rsvp_deadline' => now('Europe/Zurich')->addDays(14)->toDateString(),
        ]);
    }

    public function test_unanswered_households_with_an_address_get_the_14_day_reminder_once(): void
    {
        $due = Household::factory()->for($this->wedding)->speaking(Locale::Italian)->create(['email' => 'due@example.ch']);
        Household::factory()->for($this->wedding)->create(['email' => null]);
        Household::factory()->for($this->wedding)->create(['email' => 'done@example.ch'])->forceFill(['responded_at' => now()])->save();
        Household::factory()->for($this->wedding)->create(['email' => 'stop@example.ch'])->forceFill(['reminders_opted_out_at' => now()])->save();

        $this->artisan('hereby:send-reminders')->assertSuccessful();
        $this->artisan('hereby:send-reminders')->assertSuccessful();

        Mail::assertQueuedCount(1);
        Mail::assertQueued(GuestReminder::class, function (GuestReminder $mail) use ($due): bool {
            $mail->assertTo('due@example.ch');
            $mail->assertSeeInHtml(route('invitation.show', $due));

            return $mail->locale === 'it';
        });
        $this->assertSame(14, $due->fresh()->reminder_stage);
    }

    public function test_later_stages_follow_and_nothing_is_sent_between_them(): void
    {
        $household = Household::factory()->for($this->wedding)->create(['email' => 'due@example.ch']);
        $household->forceFill(['reminder_stage' => 14])->save();

        $this->travel(3)->days();
        $this->artisan('hereby:send-reminders');
        Mail::assertNothingQueued();

        $this->travel(4)->days();
        $this->artisan('hereby:send-reminders');
        Mail::assertQueuedCount(1);
        $this->assertSame(7, $household->fresh()->reminder_stage);
    }

    public function test_a_late_start_skips_to_the_next_stage_without_a_burst(): void
    {
        $this->wedding->update(['rsvp_deadline' => now('Europe/Zurich')->addDays(2)->toDateString()]);
        Household::factory()->for($this->wedding)->create(['email' => 'due@example.ch']);

        $this->artisan('hereby:send-reminders');

        Mail::assertQueuedCount(1);
    }

    public function test_couples_can_turn_reminders_off(): void
    {
        $this->wedding->update(['sends_reminders' => false]);
        Household::factory()->for($this->wedding)->create(['email' => 'due@example.ch']);

        $this->artisan('hereby:send-reminders');

        Mail::assertNothingQueued();
    }

    public function test_opt_out_needs_a_signed_link_and_stops_reminders(): void
    {
        $household = Household::factory()->for($this->wedding)->create(['email' => 'due@example.ch']);

        $this->get(route('invitation.reminders.stop', $household))->assertForbidden();
        $this->get(URL::signedRoute('invitation.reminders.stop', $household))->assertOk();

        $this->assertNotNull($household->fresh()->reminders_opted_out_at);
        $this->artisan('hereby:send-reminders');
        Mail::assertNothingQueued();
    }
}
