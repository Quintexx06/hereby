<?php

namespace Tests\Feature\Weddings;

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RsvpSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_couple_chooses_which_questions_guests_are_asked(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->get(route('weddings.rsvp-settings.edit', $wedding))
            ->assertInertia(fn (Assert $page) => $page
                ->component('rsvp/Settings')
                ->where('settings.asks_song', true)
                ->where('settings.menus', [])
            );

        $this->actingAs($wedding->owner)
            ->put(route('weddings.rsvp-settings.update', $wedding), [
                'menus' => ['Kalbsfilet, Kartoffelgratin', 'Risotto mit Steinpilzen', '  '],
                'children_menu' => true,
                'offers_shuttle' => true,
                'offers_stay' => false,
                'asks_song' => false,
            ])
            ->assertRedirect();

        $wedding->refresh();
        $this->assertSame(['Kalbsfilet, Kartoffelgratin', 'Risotto mit Steinpilzen'], array_column($wedding->menu_options, 'label'));
        $this->assertCount(2, array_unique(array_column($wedding->menu_options, 'key')));
        $this->assertTrue($wedding->children_menu);
        $this->assertTrue($wedding->offers_shuttle);
        $this->assertFalse($wedding->asks_song);
    }

    public function test_menu_keys_stay_stable_when_labels_are_edited(): void
    {
        $wedding = Wedding::factory()->create(['menu_options' => [['key' => 'm1', 'label' => 'Fleisch']]]);

        $this->actingAs($wedding->owner)->put(route('weddings.rsvp-settings.update', $wedding), [
            'menus' => ['Kalbsfilet'],
            'menu_keys' => ['m1'],
        ]);

        $this->assertSame([['key' => 'm1', 'label' => 'Kalbsfilet']], $wedding->fresh()->menu_options);
    }

    public function test_only_the_owner_can_change_them(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put(route('weddings.rsvp-settings.update', $wedding), ['menus' => []])
            ->assertForbidden();
    }
}
