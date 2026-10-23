<?php

namespace Tests\Feature\Invitations;

use App\Enums\EventType;
use App\Enums\Locale;
use App\Enums\WeddingTemplate;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Household;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShowInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_household_sees_only_the_events_it_is_invited_to(): void
    {
        $wedding = Wedding::factory()->create(['couple_names' => 'Anna & Luca']);
        $dinner = Event::factory()->for($wedding)->ofType(EventType::Dinner)->create();
        $civil = Event::factory()->for($wedding)->ofType(EventType::CivilCeremony)->create();

        $household = Household::factory()->for($wedding)->create(['name' => 'Familie Meier']);
        $household->events()->attach($dinner);
        Guest::factory()->for($household)->create(['first_name' => 'Heidi']);

        $this->get(route('invitation.show', $household))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('invitation/Show')
                ->where('invitation.household.name', 'Familie Meier')
                ->where('invitation.wedding.coupleNames', 'Anna & Luca')
                ->where('invitation.wedding.template', 'salon')
                ->where('invitation.guests.0.firstName', 'Heidi')
                ->has('invitation.events', 1)
                ->where('invitation.events.0.id', $dinner->id)
                ->missing('invitation.guests.0.dietaryNotes')
            );

        $this->assertNotContains($civil->id, $household->events()->pluck('events.id'));
    }

    public function test_unknown_token_returns_not_found(): void
    {
        $this->get('/i/'.str_repeat('x', Household::TOKEN_LENGTH))->assertNotFound();
    }

    public function test_first_visit_is_recorded_once(): void
    {
        $household = Household::factory()->create();

        $this->travelTo(now()->subDay());
        $this->get(route('invitation.show', $household))->assertOk();
        $firstOpenedAt = $household->fresh()->opened_at;

        $this->travelBack();
        $this->get(route('invitation.show', $household))->assertOk();

        $this->assertNotNull($firstOpenedAt);
        $this->assertTrue($firstOpenedAt->equalTo($household->fresh()->opened_at));
    }

    public function test_page_is_rendered_in_the_household_language(): void
    {
        $household = Household::factory()->speaking(Locale::French)->create();

        $this->get(route('invitation.show', $household))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('locale', 'fr')
                ->where('translations.invitation.for', 'Pour')
            );
    }

    public function test_personal_links_are_never_indexed(): void
    {
        $household = Household::factory()->create();

        $this->get(route('invitation.show', $household))
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_tokens_are_long_random_and_unique(): void
    {
        $tokens = Household::factory()->count(3)->create()->map->token;

        $this->assertSame(3, $tokens->unique()->count());
        $tokens->each(fn (string $token) => $this->assertSame(Household::TOKEN_LENGTH, strlen($token)));
    }

    public function test_tokens_are_generated_even_without_model_events(): void
    {
        $household = Household::withoutEvents(fn () => Household::factory()->create());

        $this->assertSame(Household::TOKEN_LENGTH, strlen($household->token));
    }

    public function test_malformed_token_returns_not_found(): void
    {
        $this->get('/i/short')->assertNotFound();
    }

    public function test_couple_pages_do_not_carry_the_guest_translations(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page->where('translations', []));
    }

    public function test_the_page_carries_only_its_designs_faces_and_styles(): void
    {
        $wedding = Wedding::factory()->create(['template' => WeddingTemplate::Garten]);
        $household = Household::factory()->for($wedding)->create();

        $html = (string) $this->get(route('invitation.show', $household))->assertOk()->getContent();

        $this->assertMatchesRegularExpression('/rel="preload" href="[^"]*cormorant-garamond-latin-wght-normal/', $html);
        $this->assertMatchesRegularExpression('/rel="preload" href="[^"]*archivo-latin-wght-normal/', $html);
        $this->assertDoesNotMatchRegularExpression('/rel="preload" href="[^"]*(ballet|tenor|schibsted|sofia|shrikhand|figtree)/', $html);
        $this->assertStringContainsString('.garten-arch', $html);
        $this->assertStringNotContainsString('.salon-hang', $html);
    }
}
