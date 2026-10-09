<?php

namespace Tests\Feature\Weddings;

use App\Enums\EventType;
use App\Enums\SetupStep;
use App\Enums\WeddingStatus;
use App\Enums\WeddingTheme;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WeddingSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_starting_the_setup_creates_one_draft_and_resumes_it(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('weddings.store'))
            ->assertRedirect();
        $this->actingAs($user)->post(route('weddings.store'));

        $this->assertSame(1, $user->weddings()->count());
        $this->assertSame(WeddingStatus::Draft, $user->weddings()->first()->status);
    }

    public function test_each_step_saves_and_moves_to_the_next(): void
    {
        $wedding = Wedding::factory()->draft()->create(['wedding_date' => null]);

        $this->actingAs($wedding->owner)
            ->put(route('weddings.setup.update', [$wedding, SetupStep::Couple]), [
                'partner_one' => 'Anna',
                'partner_two' => 'Luca',
            ])
            ->assertRedirect(route('weddings.setup.show', [$wedding, SetupStep::Date]));

        $wedding->refresh();
        $this->assertSame('Anna & Luca', $wedding->couple_names);
        $this->assertSame(SetupStep::Date, $wedding->setup_step);
    }

    public function test_later_steps_are_locked_until_reached(): void
    {
        $wedding = Wedding::factory()->draft(SetupStep::Date)->create();

        $this->actingAs($wedding->owner)
            ->get(route('weddings.setup.show', [$wedding, SetupStep::Look]))
            ->assertRedirect(route('weddings.setup.show', [$wedding, SetupStep::Date]));
    }

    public function test_programme_creates_events_in_swiss_time_on_the_wedding_date(): void
    {
        $wedding = Wedding::factory()->draft(SetupStep::Programme)->create([
            'wedding_date' => '2027-06-19',
            'venue_name' => 'Hotel Vitznauerhof',
        ]);

        $this->actingAs($wedding->owner)
            ->put(route('weddings.setup.update', [$wedding, SetupStep::Programme]), [
                'celebration' => 'day',
                'events' => [
                    ['type' => 'civil_ceremony', 'time' => '10:00', 'day_offset' => -1],
                    ['type' => 'dinner', 'time' => '18:30', 'day_offset' => 0],
                ],
            ])
            ->assertRedirect(route('weddings.setup.show', [$wedding, SetupStep::Guests]));

        $events = $wedding->events()->get();
        $this->assertCount(2, $events);
        $this->assertSame('2027-06-18 08:00', $events[0]->starts_at->format('Y-m-d H:i'));
        $this->assertSame(EventType::Dinner, $events[1]->type);
        $this->assertSame('Hotel Vitznauerhof', $events[1]->location_name);
        $this->assertNull($events[0]->location_name);
    }

    public function test_invalid_answers_are_rejected_with_a_message(): void
    {
        $wedding = Wedding::factory()->draft(SetupStep::Date)->create();

        $this->actingAs($wedding->owner)
            ->put(route('weddings.setup.update', [$wedding, SetupStep::Date]), ['wedding_date' => '2020-01-01'])
            ->assertSessionHasErrors('wedding_date');
    }

    public function test_look_step_suggests_a_theme_for_the_venue(): void
    {
        $wedding = Wedding::factory()->draft(SetupStep::Look)->create(['venue_postcode' => '6600', 'venue_town' => 'Locarno']);

        $this->actingAs($wedding->owner)
            ->get(route('weddings.setup.show', [$wedding, SetupStep::Look]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('setup/Step')
                ->where('suggestion.theme', WeddingTheme::Riviera->value));
    }

    public function test_completing_activates_the_wedding_or_points_to_what_is_missing(): void
    {
        $incomplete = Wedding::factory()->draft(SetupStep::Review)->create();

        $this->actingAs($incomplete->owner)
            ->post(route('weddings.setup.complete', $incomplete))
            ->assertRedirect(route('weddings.setup.show', [$incomplete, SetupStep::Programme]));

        $incomplete->update(['celebration' => 'evening', 'guest_estimate' => 'up_to_100']);
        $incomplete->events()->create(['type' => EventType::Dinner, 'starts_at' => now()->addMonths(6)]);

        $this->actingAs($incomplete->owner)
            ->post(route('weddings.setup.complete', $incomplete))
            ->assertRedirect(route('dashboard'));

        $this->assertSame(WeddingStatus::Active, $incomplete->fresh()->status);
    }

    public function test_couples_cannot_touch_other_weddings(): void
    {
        $wedding = Wedding::factory()->draft()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('weddings.setup.update', [$wedding, SetupStep::Couple]), ['partner_one' => 'X', 'partner_two' => 'Y'])
            ->assertForbidden();

        $this->actingAs(User::factory()->create())
            ->get(route('weddings.setup.show', [$wedding, SetupStep::Couple]))
            ->assertForbidden();
    }
}
