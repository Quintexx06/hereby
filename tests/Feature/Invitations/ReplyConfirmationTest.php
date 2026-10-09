<?php

namespace Tests\Feature\Invitations;

use App\Enums\EventType;
use App\Enums\Locale;
use App\Mail\ReplyConfirmation;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReplyConfirmationTest extends TestCase
{
    use RefreshDatabase;

    private Household $household;

    private Event $ceremony;

    private Event $dinner;

    private Guest $heidi;

    protected function setUp(): void
    {
        parent::setUp();

        $wedding = Wedding::factory()->create(['couple_names' => 'Anna & Luca']);
        $this->ceremony = Event::factory()->for($wedding)->ofType(EventType::Ceremony)->create(['starts_at' => Carbon::parse('2027-06-19 14:00', 'Europe/Zurich')->utc()]);
        $this->dinner = Event::factory()->for($wedding)->ofType(EventType::Dinner)->create(['starts_at' => Carbon::parse('2027-06-19 18:30', 'Europe/Zurich')->utc()]);
        $this->household = Household::factory()->for($wedding)->create(['email' => null]);
        $this->household->events()->attach([$this->ceremony->id, $this->dinner->id]);
        $this->heidi = Guest::factory()->for($this->household)->create(['first_name' => 'Heidi']);
    }

    /**
     * @return array<string, mixed>
     */
    private function reply(array $overrides = []): array
    {
        return array_merge([
            'answers' => [
                ['guest_id' => $this->heidi->id, 'event_id' => $this->ceremony->id, 'status' => 'attending'],
                ['guest_id' => $this->heidi->id, 'event_id' => $this->dinner->id, 'status' => 'declined'],
            ],
            'guests' => [['id' => $this->heidi->id, 'dietary_notes' => 'Keine Haselnüsse']],
        ], $overrides);
    }

    public function test_saving_with_an_email_sends_the_confirmation_in_the_household_language(): void
    {
        Mail::fake();
        $this->household->update(['locale' => Locale::French]);

        $this->put(route('invitation.reply.update', $this->household), $this->reply(['email' => 'heidi@example.ch']))
            ->assertRedirect();

        $this->assertSame('heidi@example.ch', $this->household->fresh()->email);
        Mail::assertQueued(ReplyConfirmation::class, function (ReplyConfirmation $mail): bool {
            $mail->assertTo('heidi@example.ch');
            $mail->assertHasSubject('Anna & Luca : votre réponse est bien arrivée');
            $mail->assertSeeInHtml('Heidi');
            $mail->assertDontSeeInHtml('Haselnüsse');
            $this->assertCount(1, $mail->attachments());

            return $mail->locale === 'fr';
        });
    }

    public function test_no_email_means_no_confirmation(): void
    {
        Mail::fake();

        $this->put(route('invitation.reply.update', $this->household), $this->reply())->assertRedirect();

        Mail::assertNothingQueued();
    }

    public function test_calendar_holds_the_attended_parts_in_utc(): void
    {
        $this->get(route('invitation.calendar', $this->household))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/calendar; charset=utf-8')
            ->assertSee('DTSTART:20270619T120000Z', false)
            ->assertSee('DTSTART:20270619T163000Z', false);

        $this->put(route('invitation.reply.update', $this->household), $this->reply());

        $this->get(route('invitation.calendar', $this->household))
            ->assertSee('DTSTART:20270619T120000Z', false)
            ->assertDontSee('DTSTART:20270619T163000Z', false);
    }

    public function test_calendar_can_be_subscribed_to_and_refreshes(): void
    {
        $this->get(route('invitation.calendar', $this->household))
            ->assertSee('X-WR-CALNAME:', false)
            ->assertSee('REFRESH-INTERVAL;VALUE=DURATION:PT12H', false);
    }
}
