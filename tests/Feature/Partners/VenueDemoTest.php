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
                ->where('demo.venue', 'Hotel Belvedere')
                ->where('demo.template', 'atelier'));

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

    public function test_a_prospect_that_says_yes_gets_its_sign_in(): void
    {
        Mail::fake();
        $prospect = Partner::factory()->create(['email' => 'events@belvedere.example']);

        $this->actingAs($this->admin())->post(route('admin.partners.resend', $prospect))->assertRedirect();

        $this->assertSame($prospect->id, User::query()->where('email', 'events@belvedere.example')->sole()->partner_id);
        Mail::assertQueued(PartnerInvitation::class);
    }
}
