<?php

namespace Tests\Feature\Extras;

use App\Enums\Addon;
use App\Enums\Plan;
use App\Models\Household;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ChaseServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_team_sees_who_to_chase_two_weeks_before_the_deadline(): void
    {
        $chased = Wedding::factory()->create(['plan' => Plan::Signature, 'rsvp_deadline' => now()->addDays(10)]);
        $chased->addons()->make()->forceFill(['addon' => Addon::ChaseService])->save();
        $late = Household::factory()->for($chased)->create(['name' => 'Familie Meier', 'phone' => '079 123 45 67']);
        Household::factory()->for($chased)->create(['name' => 'Familie Antwort', 'responded_at' => now()]);
        $notYet = Wedding::factory()->create(['plan' => Plan::Couture, 'rsvp_deadline' => now()->addDays(30)]);
        Household::factory()->for($notYet)->create(['name' => 'Familie Später']);
        $without = Wedding::factory()->create(['plan' => Plan::Atelier, 'rsvp_deadline' => now()->addDays(5)]);
        Household::factory()->for($without)->create(['name' => 'Familie Ohne']);

        $this->actingAs($this->admin())
            ->get(route('admin.chase.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/Chase')
                ->has('weddings', 1)
                ->where('weddings.0.id', $chased->id)
                ->has('weddings.0.households', 1)
                ->where('weddings.0.households.0.name', 'Familie Meier')
                ->where('weddings.0.households.0.phone', '079 123 45 67'));

        $this->actingAs($this->admin())
            ->patch(route('admin.chase.update', $late), ['chased' => true])
            ->assertRedirect();
        $this->assertNotNull($late->refresh()->chased_at);
    }

    public function test_couples_save_a_phone_number_that_guests_never_see(): void
    {
        $wedding = Wedding::factory()->create();
        $household = Household::factory()->for($wedding)->create();
        $event = $wedding->events()->create(['type' => 'dinner', 'starts_at' => now()->addMonth()]);

        $this->actingAs($wedding->owner)
            ->put(route('weddings.households.update', [$wedding, $household]), [
                'name' => $household->name,
                'email' => null,
                'phone' => '+41 79 123 45 67',
                'locale' => 'de_CH',
                'plus_one_allowed' => false,
                'events' => [$event->id],
                'guests' => [['id' => null, 'first_name' => 'Heidi', 'last_name' => null, 'is_child' => false]],
            ])
            ->assertRedirect();

        $this->assertSame('+41 79 123 45 67', $household->refresh()->phone);
        $this->get(route('invitation.show', $household))->assertDontSee('123 45 67');
    }

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }
}
