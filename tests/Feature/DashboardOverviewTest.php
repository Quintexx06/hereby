<?php

namespace Tests\Feature;

use App\Enums\ResponseStatus;
use App\Enums\SetupStep;
use App\Models\EventResponse;
use App\Models\Guest;
use App\Models\Household;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_without_a_wedding_the_dashboard_invites_to_start(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page->component('Dashboard')->where('wedding', null));
    }

    public function test_a_draft_shows_where_the_setup_stopped(): void
    {
        $wedding = Wedding::factory()->draft(SetupStep::Venue)->create();

        $this->actingAs($wedding->owner)
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('wedding.status', 'draft')
                ->where('wedding.setup_position', 3)
                ->where('overview', null));
    }

    public function test_an_active_wedding_counts_reply_status_and_suggests_next_actions(): void
    {
        $wedding = Wedding::factory()->create();
        Household::factory()->for($wedding)->has(Guest::factory()->count(2))->create();
        Household::factory()->for($wedding)->has(Guest::factory())->create(['opened_at' => now()]);
        $answered = Household::factory()->for($wedding)->has(Guest::factory())->create(['opened_at' => now()]);
        EventResponse::factory()->for($answered->guests()->first())->create(['status' => ResponseStatus::Declined]);

        $this->actingAs($wedding->owner)
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('overview.households', 3)
                ->where('overview.guests', 4)
                ->where('overview.replies.never_opened', 1)
                ->where('overview.replies.opened', 1)
                ->where('overview.replies.answered', 1)
                ->where('overview.actions.0.key', 'share_links'));
    }

    public function test_the_guest_list_shows_each_household_with_its_link(): void
    {
        $wedding = Wedding::factory()->create();
        $household = Household::factory()->for($wedding)->has(Guest::factory())->create();

        $this->actingAs($wedding->owner)
            ->get(route('weddings.guests.index', $wedding))
            ->assertInertia(fn (Assert $page) => $page
                ->component('guests/Index')
                ->where('households.0.link', route('invitation.show', $household))
                ->where('households.0.reply_status', 'never_opened'));
    }
}
