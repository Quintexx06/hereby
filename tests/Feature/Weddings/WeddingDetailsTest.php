<?php

namespace Tests\Feature\Weddings;

use App\Enums\EventType;
use App\Enums\SetupStep;
use App\Models\Event;
use App\Models\EventResponse;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WeddingDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_live_wedding_opens_its_details_and_a_draft_goes_to_setup(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->get(route('weddings.details.edit', [$wedding, 'paar']))
            ->assertInertia(fn (Assert $page) => $page
                ->component('weddings/Details')
                ->where('step', 'paar')
                ->where('wedding.partner_one', $wedding->partner_one)
            );

        $draft = Wedding::factory()->draft(SetupStep::Date)->create();

        $this->actingAs($draft->owner)
            ->get(route('weddings.details.edit', [$draft, 'ort']))
            ->assertRedirect(route('weddings.setup.show', [$draft, 'datum']));
    }

    public function test_the_couple_renames_themselves(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->put(route('weddings.details.update', [$wedding, 'paar']), ['partner_one' => 'Anna', 'partner_two' => 'Luca'])
            ->assertRedirect();

        $this->assertSame('Anna & Luca', $wedding->refresh()->couple_names);
    }

    public function test_moving_the_date_moves_the_programme_at_the_same_local_time(): void
    {
        $wedding = Wedding::factory()->create(['wedding_date' => '2027-06-19', 'rsvp_deadline' => '2027-05-01']);
        $dinner = Event::factory()->for($wedding)->create([
            'starts_at' => Carbon::parse('2027-06-19 18:30', 'Europe/Zurich')->utc(),
            'ends_at' => Carbon::parse('2027-06-19 23:00', 'Europe/Zurich')->utc(),
        ]);

        $this->actingAs($wedding->owner)
            ->put(route('weddings.details.update', [$wedding, 'datum']), ['wedding_date' => '2027-08-21', 'rsvp_deadline' => '2027-07-10'])
            ->assertRedirect();

        $dinner->refresh();
        $this->assertSame('2027-08-21 18:30', $dinner->starts_at->timezone('Europe/Zurich')->format('Y-m-d H:i'));
        $this->assertSame('2027-08-21 23:00', $dinner->ends_at?->timezone('Europe/Zurich')->format('Y-m-d H:i'));
    }

    public function test_editing_the_programme_keeps_answers_invites_to_new_parts_and_drops_removed_ones(): void
    {
        $wedding = Wedding::factory()->create(['wedding_date' => '2027-06-19']);
        $ceremony = Event::factory()->for($wedding)->ofType(EventType::Ceremony)->create();
        $dinner = Event::factory()->for($wedding)->ofType(EventType::Dinner)->create();
        $household = Household::factory()->for($wedding)->create();
        $household->events()->attach([$ceremony->id, $dinner->id]);
        $answer = EventResponse::factory()->attending()->create([
            'guest_id' => Guest::factory()->for($household),
            'event_id' => $dinner->id,
        ]);

        $this->actingAs($wedding->owner)
            ->put(route('weddings.details.update', [$wedding, 'ablauf']), [
                'celebration' => 'evening',
                'events' => [
                    ['type' => 'dinner', 'time' => '19:00', 'day_offset' => 0, 'name' => 'Abendessen'],
                    ['type' => 'party', 'time' => '22:00', 'day_offset' => 0, 'name' => null],
                ],
            ])
            ->assertRedirect();

        $dinner->refresh();
        $this->assertSame('2027-06-19 19:00', $dinner->starts_at->timezone('Europe/Zurich')->format('Y-m-d H:i'));
        $this->assertSame('Abendessen', $dinner->name);
        $this->assertModelExists($answer);
        $this->assertModelMissing($ceremony);

        $party = $wedding->events()->where('type', EventType::Party)->sole();
        $this->assertTrue($household->events()->whereKey($party->id)->exists());
    }

    public function test_couples_cannot_edit_other_weddings(): void
    {
        $wedding = Wedding::factory()->create();
        $stranger = Wedding::factory()->create()->owner;

        $this->actingAs($stranger)
            ->put(route('weddings.details.update', [$wedding, 'paar']), ['partner_one' => 'X', 'partner_two' => 'Y'])
            ->assertForbidden();
    }

    public function test_the_couple_picks_an_own_colour_and_leaves_out_built_in_parts(): void
    {
        $wedding = Wedding::factory()->create();
        $household = Household::factory()->for($wedding)->create();

        $this->actingAs($wedding->owner)
            ->put(route('weddings.details.update', [$wedding, 'look']), [
                'theme' => 'ivory',
                'template' => 'garten',
                'accent_color' => '#2E7D5B',
                'hidden_sections' => ['opening', 'ornaments'],
            ])
            ->assertSessionHasNoErrors();

        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page
                ->where('invitation.wedding.accentColor', '#2e7d5b')
                ->where('invitation.wedding.hiddenSections', ['opening', 'ornaments'])
            );

        $this->actingAs($wedding->owner)
            ->put(route('weddings.details.update', [$wedding, 'look']), [
                'theme' => 'ivory',
                'template' => 'garten',
                'accent_color' => 'grün',
                'hidden_sections' => ['reply'],
            ])
            ->assertSessionHasErrors(['accent_color', 'hidden_sections.0']);
    }
}
