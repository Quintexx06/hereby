<?php

namespace Tests\Feature\Extras;

use App\Enums\Addon;
use App\Enums\Plan;
use App\Models\Household;
use App\Models\User;
use App\Models\Wedding;
use App\Support\PersonalLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CustomDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_couple_enters_a_domain_and_links_change_once_the_team_connects_it(): void
    {
        $wedding = $this->weddingWithDomainAddon();
        $household = Household::factory()->for($wedding)->create();

        $this->actingAs($wedding->owner)
            ->put(route('weddings.extras.domain', $wedding), ['domain' => 'https://Anna-und-Luca.ch/'])
            ->assertRedirect();

        $this->assertSame('anna-und-luca.ch', $wedding->refresh()->custom_domain);
        $this->assertStringStartsWith(config('app.url'), PersonalLink::to($household));

        $this->actingAs($this->admin())
            ->patch(route('admin.weddings.domain', $wedding), ['connected' => true])
            ->assertRedirect();

        $this->assertSame('https://anna-und-luca.ch/i/'.$household->token, PersonalLink::to($household->refresh()));
        $this->actingAs($wedding->owner)
            ->get(route('weddings.guests.index', $wedding))
            ->assertInertia(fn (Assert $page) => $page->where('households.0.link', 'https://anna-und-luca.ch/i/'.$household->token));

        $this->actingAs($wedding->owner)
            ->put(route('weddings.extras.domain', $wedding), ['domain' => 'anna-luca.ch'])
            ->assertRedirect();
        $this->assertNull($wedding->refresh()->custom_domain_verified_at);
    }

    public function test_a_connected_domain_serves_only_its_own_guest_pages(): void
    {
        $wedding = $this->weddingWithDomainAddon(['custom_domain' => 'anna-und-luca.ch', 'custom_domain_verified_at' => now()]);
        $own = Household::factory()->for($wedding)->create();
        $foreign = Household::factory()->for(Wedding::factory())->create();

        $this->get('https://anna-und-luca.ch/i/'.$own->token)->assertOk();
        $this->get('https://anna-und-luca.ch/i/'.$foreign->token)->assertNotFound();
        $this->get('https://anna-und-luca.ch/')->assertNotFound();
        $this->get('https://anna-und-luca.ch/dashboard')->assertNotFound();
        $this->get('https://anna-und-luca.ch/login')->assertNotFound();
    }

    public function test_only_couples_with_the_extra_set_a_valid_unused_domain(): void
    {
        $without = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $this->actingAs($without->owner)
            ->put(route('weddings.extras.domain', $without), ['domain' => 'anna.ch'])
            ->assertForbidden();

        $this->weddingWithDomainAddon(['custom_domain' => 'taken.ch']);
        $wedding = $this->weddingWithDomainAddon();
        $this->actingAs($wedding->owner)
            ->put(route('weddings.extras.domain', $wedding), ['domain' => 'taken.ch'])
            ->assertSessionHasErrors('domain');
        $this->actingAs($wedding->owner)
            ->put(route('weddings.extras.domain', $wedding), ['domain' => 'not a domain'])
            ->assertSessionHasErrors('domain');

        config(['app.url' => 'https://hereby.ch']);
        foreach (['hereby.ch', 'www.hereby.ch', 'anna.hereby.ch'] as $ours) {
            $this->actingAs($wedding->owner)
                ->put(route('weddings.extras.domain', $wedding), ['domain' => $ours])
                ->assertSessionHasErrors('domain');
        }
        $this->actingAs($wedding->owner)
            ->put(route('weddings.extras.domain', $wedding), ['domain' => 'notherebych.ch'])
            ->assertSessionHasNoErrors();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function weddingWithDomainAddon(array $attributes = []): Wedding
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Signature, ...$attributes]);
        $wedding->addons()->make()->forceFill(['addon' => Addon::CustomDomain])->save();

        return $wedding->refresh();
    }

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }
}
