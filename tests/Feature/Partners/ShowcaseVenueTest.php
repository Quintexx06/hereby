<?php

namespace Tests\Feature\Partners;

use App\Mail\VenueEnquiryReceived;
use App\Models\Household;
use App\Models\Partner;
use App\Models\User;
use App\Models\VenueEnquiry;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShowcaseVenueTest extends TestCase
{
    use RefreshDatabase;

    private function userFor(Partner $partner): User
    {
        $user = User::factory()->create();
        $user->partner_id = $partner->id;
        $user->save();

        return $user;
    }

    public function test_a_showcase_venue_shows_a_couple_their_site_on_the_tour(): void
    {
        $venue = Partner::factory()->showcase()->create(['name' => 'Seehotel Kastanienbaum', 'travel' => 'Bus 21 ab Luzern.']);

        $this->actingAs($this->userFor($venue))
            ->get(route('partner.preview', ['erste' => 'Lea', 'zweite' => 'Jonas', 'datum' => now()->addMonths(8)->toDateString(), 'vorlage' => 'garten']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('invitation/Show')
                ->where('invitation.wedding.coupleNames', 'Lea & Jonas')
                ->where('invitation.wedding.template', 'garten')
                ->where('invitation.wedding.venueCredit.name', 'Seehotel Kastanienbaum')
                ->has('invitation.events', 4)
            );

        $this->assertDatabaseCount('weddings', 0);
    }

    public function test_only_showcase_venues_get_the_preview_and_a_public_page(): void
    {
        $venue = Partner::factory()->create();

        $this->actingAs($this->userFor($venue))->get(route('partner.preview'))->assertForbidden();
        $this->get(route('venues.show', $venue->slug))->assertNotFound();
    }

    public function test_a_guest_asks_for_a_tour_and_the_venue_gets_it_by_mail(): void
    {
        Mail::fake();
        $venue = Partner::factory()->showcase()->create(['email' => 'events@seehotel.ch']);

        $this->get(route('venues.show', $venue->slug))->assertOk();
        $this->post(route('venues.enquiry', $venue->slug), ['name' => 'Mia', 'email' => 'mia@example.ch', 'message' => 'Juni 2028?'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertSame('Mia', $venue->enquiries()->sole()->name);
        Mail::assertQueued(VenueEnquiryReceived::class, fn (VenueEnquiryReceived $mail): bool => $mail->hasTo('events@seehotel.ch'));
    }

    public function test_guests_see_one_credit_line_for_a_connected_showcase_venue_only(): void
    {
        $wedding = Wedding::factory()->create();
        $household = Household::factory()->for($wedding)->create();
        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page->where('invitation.wedding.venueCredit', null));

        $venue = Partner::factory()->showcase()->create(['name' => 'Seehotel Kastanienbaum']);
        $wedding->partners()->attach($venue->id, ['created_at' => now()]);

        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page->where('invitation.wedding.venueCredit.name', 'Seehotel Kastanienbaum'));
    }

    public function test_tour_requests_are_deleted_after_twelve_months(): void
    {
        $old = VenueEnquiry::factory()->create();
        $old->forceFill(['created_at' => now()->subMonths(13)])->save();
        $recent = VenueEnquiry::factory()->create();

        $this->artisan('model:prune', ['--model' => [VenueEnquiry::class]]);

        $this->assertModelMissing($old);
        $this->assertModelExists($recent);
    }
}
