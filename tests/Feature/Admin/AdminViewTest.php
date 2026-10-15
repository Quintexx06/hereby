<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_admin_starts_in_the_team_view_and_can_switch_to_their_own_wedding(): void
    {
        $wedding = Wedding::factory()->create();
        $admin = $wedding->owner;
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin)->get(route('dashboard'))->assertRedirect(route('admin.weddings.index'));
        $this->get(route('admin.weddings.index'))
            ->assertInertia(fn (Assert $page) => $page->where('adminView', 'team')->where('currentWedding', null));

        $this->post(route('admin.view'), ['view' => 'couple'])->assertRedirect(route('dashboard'));
        $this->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page->component('Dashboard')->where('adminView', 'couple')->where('currentWedding.id', $wedding->id));
    }

    public function test_in_the_team_view_a_couples_pages_show_that_couples_wedding(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $wedding = Wedding::factory()->create();

        $this->actingAs($admin)->get(route('weddings.partners.index', $wedding))
            ->assertInertia(fn (Assert $page) => $page->where('currentWedding.id', $wedding->id));
    }

    public function test_couples_have_no_view_to_switch(): void
    {
        $couple = Wedding::factory()->create()->owner;

        $this->actingAs($couple)->post(route('admin.view'), ['view' => 'team'])->assertForbidden();
        $this->get(route('dashboard'))->assertInertia(fn (Assert $page) => $page->where('adminView', null));
    }
}
