<?php

namespace Tests\Feature\Evening;

use App\Enums\Locale;
use App\Enums\Plan;
use App\Mail\WayHomeReminder;
use App\Models\Event;
use App\Models\EventResponse;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WayHomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_couple_lists_the_last_connections_of_the_night(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);

        $this->actingAs($wedding->owner)
            ->put(route('weddings.way-home.update', $wedding), ['connections' => [
                ['label' => 'Letztes Schiff nach Luzern', 'place' => 'Steg Vitznau', 'time' => '00:40'],
                ['label' => 'Shuttle nach Zürich', 'place' => null, 'time' => '23:30'],
            ]])
            ->assertRedirect();

        $this->assertSame('Letztes Schiff nach Luzern', $wedding->refresh()->way_home[0]['label']);

        $this->actingAs($wedding->owner)
            ->put(route('weddings.way-home.update', $wedding), ['connections' => [['label' => 'Bus', 'time' => '25:00']]])
            ->assertSessionHasErrors('connections.0.time');
    }

    public function test_guests_see_the_connections_and_times_before_noon_belong_to_the_night_after(): void
    {
        $wedding = Wedding::factory()->create([
            'plan' => Plan::Atelier,
            'wedding_date' => '2027-06-19',
            'way_home' => [
                ['label' => 'Letztes Schiff', 'place' => 'Steg Vitznau', 'time' => '00:40'],
                ['label' => 'Shuttle', 'place' => null, 'time' => '23:30'],
            ],
        ]);
        $household = Household::factory()->for($wedding)->create();

        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page
                ->where('invitation.evening.wayHome.0.label', 'Shuttle')
                ->where('invitation.evening.wayHome.0.departsAt', '2027-06-19T23:30:00+02:00')
                ->where('invitation.evening.wayHome.1.departsAt', '2027-06-20T00:40:00+02:00'));
    }

    public function test_attending_households_get_one_mail_half_an_hour_before(): void
    {
        Mail::fake();
        $wedding = Wedding::factory()->create([
            'plan' => Plan::Atelier,
            'wedding_date' => '2027-06-19',
            'way_home' => [['label' => 'Letztes Schiff', 'place' => 'Steg Vitznau', 'time' => '00:40']],
        ]);
        $event = Event::factory()->for($wedding)->create();
        $coming = $this->household($wedding, $event, attending: true, email: 'heidi@example.ch');
        $this->household($wedding, $event, attending: false, email: 'absent@example.ch');
        $this->household($wedding, $event, attending: true, email: null);

        $this->travelTo(CarbonImmutable::parse('2027-06-19 23:45', 'Europe/Zurich'));
        $this->artisan('hereby:send-way-home')->assertSuccessful();
        Mail::assertNothingQueued();

        $this->travelTo(CarbonImmutable::parse('2027-06-20 00:12', 'Europe/Zurich'));
        $this->artisan('hereby:send-way-home')->assertSuccessful();
        $this->artisan('hereby:send-way-home')->assertSuccessful();

        Mail::assertQueued(WayHomeReminder::class, 1);
        Mail::assertQueued(WayHomeReminder::class, fn (WayHomeReminder $mail): bool => $mail->hasTo('heidi@example.ch')
            && $mail->departure['label'] === 'Letztes Schiff');
        $this->assertNotNull($coming);
    }

    public function test_the_mail_names_the_connection_in_the_guests_language(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $household = Household::factory()->for($wedding)->speaking(Locale::English)->create();

        $mail = (new WayHomeReminder($household, ['label' => 'Last boat', 'place' => 'Vitznau pier', 'time' => '00:40']))->locale('en');

        $mail->assertHasSubject('In half an hour: Last boat at 00:40');
        $mail->assertSeeInHtml('Vitznau pier');
        $mail->assertSeeInHtml(route('invitation.show', $household));
    }

    public function test_no_way_home_without_atelier(): void
    {
        Mail::fake();
        $wedding = Wedding::factory()->create([
            'plan' => Plan::Signature,
            'wedding_date' => '2027-06-19',
            'way_home' => [['label' => 'Letztes Schiff', 'place' => null, 'time' => '00:40']],
        ]);
        $this->household($wedding, Event::factory()->for($wedding)->create(), attending: true, email: 'heidi@example.ch');

        $this->travelTo(CarbonImmutable::parse('2027-06-20 00:12', 'Europe/Zurich'));
        $this->artisan('hereby:send-way-home')->assertSuccessful();

        Mail::assertNothingQueued();
        $this->actingAs($wedding->owner)
            ->put(route('weddings.way-home.update', $wedding), ['connections' => []])
            ->assertForbidden();
    }

    private function household(Wedding $wedding, Event $event, bool $attending, ?string $email): Household
    {
        $household = Household::factory()->for($wedding)->speaking(Locale::GermanSwiss)->create(['email' => $email]);
        $guest = Guest::factory()->for($household)->create();
        $household->events()->attach($event);
        $factory = EventResponse::factory()->for($guest)->for($event);
        ($attending ? $factory->attending() : $factory)->create();

        return $household;
    }
}
