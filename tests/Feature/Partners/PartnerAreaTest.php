<?php

namespace Tests\Feature\Partners;

use App\Enums\EventType;
use App\Enums\Plan;
use App\Enums\ResponseStatus;
use App\Models\Event;
use App\Models\Guest;
use App\Models\GuestPhoto;
use App\Models\Household;
use App\Models\Partner;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PartnerAreaTest extends TestCase
{
    use RefreshDatabase;

    private Wedding $wedding;

    protected function setUp(): void
    {
        parent::setUp();

        $this->wedding = Wedding::factory()->create(['couple_names' => 'Lea & Jonas', 'plan' => Plan::Atelier]);
        $dinner = Event::factory()->for($this->wedding)->ofType(EventType::Dinner)->create();
        $household = Household::factory()->for($this->wedding)->create(['name' => 'Familie Meier', 'responded_at' => now()]);
        $household->events()->attach($dinner);
        $heidi = Guest::factory()->for($household)->create(['first_name' => 'Heidi', 'dietary_notes' => 'Keine Haselnüsse']);
        $heidi->responses()->create(['event_id' => $dinner->id, 'status' => ResponseStatus::Attending]);
    }

    private function partnerUser(Partner $partner, bool $connected = true): User
    {
        if ($connected) {
            $this->wedding->partners()->attach($partner->id, ['created_at' => now()]);
        }

        $user = User::factory()->create();
        $user->partner_id = $partner->id;
        $user->save();

        return $user;
    }

    public function test_a_venue_sees_its_connected_weddings_with_counts_and_no_names(): void
    {
        $venue = $this->partnerUser(Partner::factory()->create());
        Wedding::factory()->create(['couple_names' => 'Nicht verbunden']);

        $response = $this->actingAs($venue)->get(route('partner.calendar'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('partner/Calendar')
            ->has('weddings', 1)
            ->where('weddings.0.couple_names', 'Lea & Jonas')
            ->where('weddings.0.answered', 1)
            ->where('weddings.0.attending', 1)
            ->where('weddings.0.events.0.attending', 1)
        );
        $this->assertStringNotContainsString('Heidi', $response->getContent() ?: '');
        $this->assertStringNotContainsString('Haselnüsse', $response->getContent() ?: '');
    }

    public function test_the_venue_prints_the_final_numbers_of_connected_weddings_only(): void
    {
        $venue = $this->partnerUser(Partner::factory()->create());

        $this->wedding->households()->first()?->guests()->update(['table_name' => 'Rigi']);

        // Allergies per table, as promised: never a guest's or household's name.
        $this->actingAs($venue)->get(route('partner.sheet', $this->wedding))
            ->assertOk()
            ->assertSee('Keine Haselnüsse')
            ->assertSee('Rigi')
            ->assertDontSee('Heidi')
            ->assertDontSee('Familie Meier');
        $this->actingAs($venue)->get(route('partner.sheet', Wedding::factory()->create()))->assertForbidden();
    }

    public function test_a_photographer_uploads_into_the_couples_memories_but_never_sees_the_sheet(): void
    {
        Storage::fake(GuestPhoto::DISK);
        $photographer = $this->partnerUser(Partner::factory()->photographer()->create());

        $this->actingAs($photographer)->get(route('partner.sheet', $this->wedding))->assertForbidden();
        $this->actingAs($photographer)->get(route('partner.photos.show', $this->wedding))->assertOk();
        $this->actingAs($photographer)
            ->post(route('partner.photos.store', $this->wedding), ['photo' => UploadedFile::fake()->image('kuss.jpg', 1200, 800)])
            ->assertCreated();

        $this->assertSame($photographer->partner_id, $this->wedding->photos()->sole()->partner_id);
    }

    public function test_couples_have_no_partner_area_and_partners_start_on_their_calendar(): void
    {
        $this->actingAs($this->wedding->owner)->get(route('partner.calendar'))->assertForbidden();

        $planner = $this->partnerUser(Partner::factory()->planner()->create(), connected: false);
        $this->actingAs($planner)->get(route('dashboard'))->assertRedirect(route('partner.calendar'));
        $this->actingAs($planner)->get(route('partner.sheet', $this->wedding))->assertForbidden();
    }

    public function test_a_partner_keeps_its_profile_up_to_date(): void
    {
        $venue = $this->partnerUser(Partner::factory()->create());

        $this->actingAs($venue)
            ->put(route('partner.profile.update'), ['name' => 'Seehotel Kastanienbaum', 'postcode' => '6047', 'travel' => 'Bus 21.'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertSame('6047', $venue->partner?->refresh()->postcode);
    }
}
