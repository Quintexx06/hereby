<?php

namespace Tests\Feature\Partners;

use App\Mail\PartnerInvitation;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class VenueDemoTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }

    public function test_the_team_adds_a_prospect_without_sign_in_or_mail_and_gets_its_demo_link(): void
    {
        Mail::fake();

        $this->actingAs($this->admin())
            ->post(route('admin.partners.store'), ['type' => 'venue', 'name' => 'Hotel Belvedere', 'town' => 'Spiez', 'without_login' => true])
            ->assertSessionHasNoErrors();

        $prospect = Partner::query()->sole();
        $this->assertSame('Spiez', $prospect->town);
        $this->assertFalse($prospect->users()->exists());
        Mail::assertNothingQueued();

        $this->get(route('admin.partners.index'))
            ->assertInertia(fn (Assert $page) => $page->where('partners.0.demo_url', fn (string $url) => str_contains($url, '/demo/'.$prospect->slug.'?expires=')));
    }

    public function test_the_signed_demo_shows_the_venues_own_site_in_every_template(): void
    {
        $venue = Partner::factory()->create(['name' => 'Hotel Belvedere', 'town' => 'Spiez']);
        $url = URL::temporarySignedRoute('venues.demo', now()->addDays(90), ['partner' => $venue->slug]);

        $this->get($url)->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertInertia(fn (Assert $page) => $page->component('invitation/Show')
                ->where('invitation.wedding.coupleNames', 'Anna & Luca')
                ->where('translations.invitation.countdown', fn (string $line) => str_contains($line, 'Noch :count Tage'))
                ->where('demo.venue', 'Hotel Belvedere')
                ->where('demo.template', 'atelier')
                ->where('invitation.guests.0.firstName', 'Sandra')
                ->where('invitation.wedding.photos.0.url', fn (string $url) => str_ends_with($url, '/images/landing/veil-kiss.webp'))
                ->where('invitation.blocks', fn ($blocks) => collect($blocks)->pluck('type')->intersect(['story', 'venue', 'dress_code', 'faq'])->count() === 4));

        $this->get($url.'&vorlage=garten')->assertOk()->assertInertia(fn (Assert $page) => $page->where('demo.template', 'garten'));
    }

    public function test_an_unsigned_expired_or_non_venue_demo_does_not_open(): void
    {
        $venue = Partner::factory()->create();
        $photographer = Partner::factory()->photographer()->create();

        $this->get(route('venues.demo', $venue->slug))->assertForbidden();
        $this->get(URL::temporarySignedRoute('venues.demo', now()->subMinute(), ['partner' => $venue->slug]))->assertForbidden();
        $this->get(URL::temporarySignedRoute('venues.demo', now()->addDay(), ['partner' => $photographer->slug]))->assertNotFound();
    }

    public function test_without_a_street_the_route_leads_to_the_venue_not_the_village(): void
    {
        $venue = Partner::factory()->create(['name' => 'Stallstube Riethof', 'address' => null, 'postcode' => null, 'town' => 'Dachsen']);

        $this->get(URL::temporarySignedRoute('venues.demo', now()->addDay(), ['partner' => $venue->slug]))
            ->assertInertia(fn (Assert $page) => $page->where('invitation.blocks', fn ($blocks) => collect($blocks)->firstWhere('type', 'venue')['venue']['route']
                === 'https://www.google.com/maps/dir/?api=1&destination='.urlencode('Stallstube Riethof, Dachsen')));
    }

    public function test_the_team_gives_a_prospect_its_street_and_the_demo_routes_to_it(): void
    {
        $venue = Partner::factory()->create(['name' => 'Stallstube Riethof', 'town' => 'Dachsen']);
        $admin = $this->admin();

        $this->actingAs($admin)->patch(route('admin.partners.update', $venue), ['postcode' => '84470'])
            ->assertSessionHasErrors('postcode');
        $this->actingAs($admin)->patch(route('admin.partners.update', $venue), ['address' => 'Riethof 1', 'postcode' => '8447'])
            ->assertSessionHasNoErrors();

        $this->get(route('admin.partners.index'))
            ->assertInertia(fn (Assert $page) => $page->where('partners.0.address', 'Riethof 1')->where('partners.0.postcode', '8447'));
        $this->get(URL::temporarySignedRoute('venues.demo', now()->addDay(), ['partner' => $venue->slug]))
            ->assertInertia(fn (Assert $page) => $page->where('invitation.blocks', fn ($blocks) => collect($blocks)->firstWhere('type', 'venue')['venue']['address']
                === 'Riethof 1, 8447 Dachsen'));
    }

    public function test_a_prospect_that_says_yes_gets_its_sign_in(): void
    {
        Mail::fake();
        $prospect = Partner::factory()->create(['email' => 'events@belvedere.example']);

        $this->actingAs($this->admin())->post(route('admin.partners.resend', $prospect))->assertRedirect();

        $this->assertSame($prospect->id, User::query()->where('email', 'events@belvedere.example')->sole()->partner_id);
        Mail::assertQueued(PartnerInvitation::class);
    }

    public function test_a_visitor_replies_on_the_demo_like_a_guest_and_nothing_is_stored(): void
    {
        $venue = Partner::factory()->create(['name' => 'Hotel Belvedere']);
        $demo = URL::temporarySignedRoute('venues.demo', now()->addDays(30), ['partner' => $venue->slug]);

        $reply = '';
        $this->get($demo)->assertInertia(function (Assert $page) use (&$reply) {
            $reply = $page->toArray()['props']['invitation']['links']['reply'];
        });
        $this->assertStringContainsString('/demo/'.$venue->slug.'/antworten', $reply);

        $update = '';
        $this->get($reply)->assertOk()->assertInertia(function (Assert $page) use (&$update) {
            $page->component('invitation/Reply')->where('reply.guests.0.firstName', 'Sandra')->where('reply.questions.menus.2.label', 'Vegetarisch');
            $update = $page->toArray()['props']['reply']['links']['reply'];
        });

        $this->put($update, ['answers' => [['guest_id' => 1, 'event_id' => 1, 'status' => 'attending', 'menu' => null], ['guest_id' => 1, 'event_id' => 3, 'status' => 'attending', 'menu' => 'Fisch']]])
            ->assertRedirect();

        $this->followingRedirects()->put($update, ['answers' => [['event_id' => 1, 'status' => 'attending']]])
            ->assertInertia(fn (Assert $page) => $page->where('replied', true)->where('invitation.reply.answered', true)->where('invitation.reply.attending', 1));

        $this->assertDatabaseCount('event_responses', 0);
        $this->assertDatabaseCount('households', 0);

        $this->get(str_replace('/antworten', '/kalender.ics', explode('&vorlage', $reply)[0]))->assertForbidden();
        $this->put(explode('?', $update)[0], ['answers' => []])->assertForbidden();
    }

    public function test_the_demo_calendar_downloads_and_its_links_keep_the_original_expiry(): void
    {
        $venue = Partner::factory()->create();
        $expires = now()->addDays(10)->getTimestamp();
        $demo = URL::temporarySignedRoute('venues.demo', now()->addDays(10), ['partner' => $venue->slug]);

        $this->get($demo)->assertInertia(function (Assert $page) use ($expires) {
            $calendar = $page->toArray()['props']['invitation']['links']['calendar'];
            $this->assertStringContainsString("expires={$expires}", $calendar);
            $this->get($calendar)->assertOk()->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
        });
    }
}
