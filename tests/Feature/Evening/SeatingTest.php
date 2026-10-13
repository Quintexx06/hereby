<?php

namespace Tests\Feature\Evening;

use App\Enums\Plan;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SeatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_couple_seats_guests_with_a_note_and_publishes_the_plan(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $lena = Guest::factory()->for(Household::factory()->for($wedding))->create(['first_name' => 'Lena']);

        $this->actingAs($wedding->owner)
            ->get(route('weddings.seating.edit', $wedding))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('evening/Seating')
                ->where('unlocked', true)
                ->where('households.0.guests.0.first_name', 'Lena'));

        $this->actingAs($wedding->owner)
            ->put(route('weddings.seating.update', $wedding), [
                'published' => true,
                'guests' => [['id' => $lena->id, 'table_name' => 'Rigi', 'table_note' => 'Annas Studienfreundin. Fragt sie nach Island.']],
            ])
            ->assertRedirect();

        $this->assertSame('Rigi', $lena->refresh()->table_name);
        $this->assertNotNull($wedding->refresh()->seating_published_at);
    }

    public function test_guests_of_another_wedding_cannot_be_seated(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $stranger = Guest::factory()->for(Household::factory()->for(Wedding::factory()))->create();

        $this->actingAs($wedding->owner)
            ->put(route('weddings.seating.update', $wedding), [
                'published' => false,
                'guests' => [['id' => $stranger->id, 'table_name' => 'Rigi', 'table_note' => null]],
            ])
            ->assertSessionHasErrors('guests.0.id');

        $this->assertNull($stranger->refresh()->table_name);
    }

    public function test_seating_needs_atelier(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Signature]);

        $this->actingAs($wedding->owner)
            ->get(route('weddings.seating.edit', $wedding))
            ->assertInertia(fn (Assert $page) => $page->where('unlocked', false)->where('households', []));
        $this->actingAs($wedding->owner)
            ->put(route('weddings.seating.update', $wedding), ['published' => true, 'guests' => []])
            ->assertForbidden();
    }

    public function test_guests_see_their_table_mates_only_once_the_plan_is_published(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $household = Household::factory()->for($wedding)->create();
        Guest::factory()->for($household)->create(['first_name' => 'Heidi', 'table_name' => 'Rigi']);
        Guest::factory()->for(Household::factory()->for($wedding))->create([
            'first_name' => 'Lena', 'last_name' => 'Keller', 'table_name' => 'Rigi', 'table_note' => 'Fragt sie nach Island.', 'dietary_notes' => 'Nüsse',
        ]);
        Guest::factory()->for(Household::factory()->for($wedding))->create(['first_name' => 'Marco', 'table_name' => 'Pilatus']);

        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page->where('invitation.evening.tables', []));

        $wedding->forceFill(['seating_published_at' => now()])->save();

        $response = $this->get(route('invitation.show', $household));
        $response->assertInertia(fn (Assert $page) => $page
            ->where('invitation.evening.tables.0.name', 'Rigi')
            ->where('invitation.evening.tables.0.yours', ['Heidi'])
            ->where('invitation.evening.tables.0.mates', [['name' => 'Lena', 'note' => 'Fragt sie nach Island.']]));
        $response->assertDontSee('Keller')->assertDontSee('Nüsse')->assertDontSee('Marco');
    }

    public function test_guests_without_atelier_get_no_evening(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Signature, 'seating_published_at' => now()]);
        $household = Household::factory()->for($wedding)->create();
        Guest::factory()->for($household)->create(['table_name' => 'Rigi']);

        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page->where('invitation.evening', null));
    }
}
