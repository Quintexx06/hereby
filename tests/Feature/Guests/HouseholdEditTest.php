<?php

namespace Tests\Feature\Guests;

use App\Enums\ResponseStatus;
use App\Models\Event;
use App\Models\EventResponse;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HouseholdEditTest extends TestCase
{
    use RefreshDatabase;

    private function wedding(): Wedding
    {
        return Wedding::factory()->create();
    }

    public function test_editing_renames_people_removes_some_adds_others_and_narrows_events(): void
    {
        $wedding = $this->wedding();
        [$dinner, $party] = Event::factory()->for($wedding)->count(2)->create();
        $household = Household::factory()->for($wedding)->create();
        $household->events()->attach([$dinner->id, $party->id]);
        [$heidi, $peter] = Guest::factory()->for($household)->count(2)->create();
        EventResponse::factory()->for($peter)->for($dinner)->create(['status' => ResponseStatus::Attending]);

        $this->actingAs($wedding->owner)
            ->put(route('weddings.households.update', [$wedding, $household]), [
                'name' => 'Familie Meier',
                'email' => 'meier@example.ch',
                'locale' => 'fr',
                'plus_one_allowed' => true,
                'events' => [$party->id],
                'guests' => [
                    ['id' => $heidi->id, 'first_name' => 'Heidi', 'last_name' => 'Meier'],
                    ['first_name' => 'Lina', 'last_name' => 'Meier', 'is_child' => true],
                ],
            ])
            ->assertRedirect();

        $household->refresh();
        $this->assertSame('Familie Meier', $household->name);
        $this->assertTrue($household->plus_one_allowed);
        $this->assertSame([$party->id], $household->events()->pluck('events.id')->all());
        $this->assertEqualsCanonicalizing(['Heidi', 'Lina'], $household->guests()->pluck('first_name')->all());
        $this->assertModelMissing($peter);
        $this->assertSame(0, EventResponse::count());
    }

    public function test_events_from_another_wedding_are_rejected(): void
    {
        $wedding = $this->wedding();
        $household = Household::factory()->for($wedding)->has(Guest::factory())->create();
        $foreign = Event::factory()->create();

        $this->actingAs($wedding->owner)
            ->put(route('weddings.households.update', [$wedding, $household]), [
                'name' => 'X', 'locale' => 'de_CH', 'events' => [$foreign->id],
                'guests' => [['first_name' => 'Heidi']],
            ])
            ->assertSessionHasErrors('events.0');
    }

    public function test_a_household_is_only_reachable_through_its_own_wedding(): void
    {
        $wedding = $this->wedding();
        $other = Household::factory()->create();

        $this->actingAs($wedding->owner)
            ->delete(route('weddings.households.destroy', [$wedding, $other]))
            ->assertNotFound();

        $this->assertModelExists($other);
    }

    public function test_renewing_the_link_invalidates_the_old_one(): void
    {
        $wedding = $this->wedding();
        $household = Household::factory()->for($wedding)->create(['opened_at' => now()]);
        $oldToken = $household->token;

        $this->actingAs($wedding->owner)
            ->post(route('weddings.households.renew-link', [$wedding, $household]))
            ->assertRedirect();

        $household->refresh();
        $this->assertNotSame($oldToken, $household->token);
        $this->assertNull($household->opened_at);
        $this->get('/i/'.$oldToken)->assertNotFound();
    }

    public function test_removing_a_household_removes_its_people(): void
    {
        $wedding = $this->wedding();
        $household = Household::factory()->for($wedding)->has(Guest::factory())->create();

        $this->actingAs($wedding->owner)
            ->delete(route('weddings.households.destroy', [$wedding, $household]))
            ->assertRedirect(route('weddings.guests.index', $wedding));

        $this->assertModelMissing($household);
        $this->assertSame(0, Guest::count());
    }
}
