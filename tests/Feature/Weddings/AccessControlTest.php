<?php

namespace Tests\Feature\Weddings;

use App\Enums\SetupStep;
use App\Models\ContentBlock;
use App\Models\GuestPhoto;
use App\Models\Household;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Who may reach what, checked route by route, so a route added later cannot
 * ship without its check: couples reach only their own wedding, the admin
 * area is the team's, and nothing but the two outside callers skips CSRF.
 */
class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<int, array<string, int|string>> Route parameters per wedding. */
    private array $parameters = [];

    public function test_every_wedding_route_is_closed_to_another_couple_and_to_visitors(): void
    {
        Http::fake();
        $wedding = Wedding::factory()->create();
        $stranger = User::factory()->create();
        $routes = $this->routesWhere(fn (RoutingRoute $route): bool => str_contains($route->uri(), '{wedding}')
            && ! str_starts_with($route->uri(), 'admin/')
            && ! $route->named('invitation.*'));

        $this->assertNotEmpty($routes);

        foreach ($routes as $route) {
            [$method, $url] = [$route->methods()[0], $this->urlFor($route, $wedding)];

            $this->actingAs($stranger)->call($method, $url)
                ->assertForbidden("{$method} {$route->uri()} is open to another couple.");

            auth()->logout();
            $this->call($method, $url)->assertRedirect(route('login'));
        }
    }

    public function test_every_admin_route_is_closed_to_couples(): void
    {
        $wedding = Wedding::factory()->create();
        $routes = $this->routesWhere(fn (RoutingRoute $route): bool => str_starts_with($route->uri(), 'admin/'));

        $this->assertNotEmpty($routes);

        foreach ($routes as $route) {
            $this->actingAs($wedding->owner)->call($route->methods()[0], $this->urlFor($route, $wedding))
                ->assertForbidden("{$route->methods()[0]} {$route->uri()} is open to couples.");
        }
    }

    public function test_only_stripe_and_the_one_click_opt_out_skip_the_csrf_check(): void
    {
        $exempt = array_map(
            fn (RoutingRoute $route): ?string => $route->getName(),
            $this->routesWhere(fn (RoutingRoute $route): bool => in_array(PreventRequestForgery::class, $route->excludedMiddleware(), true)),
        );

        $this->assertEqualsCanonicalizing(['stripe.webhook', 'invitation.reminders.stop'], $exempt);
    }

    /**
     * @param  callable(RoutingRoute): bool  $filter
     * @return list<RoutingRoute>
     */
    private function routesWhere(callable $filter): array
    {
        return array_values(array_filter(Route::getRoutes()->getRoutes(), $filter));
    }

    private function urlFor(RoutingRoute $route, Wedding $wedding): string
    {
        $this->parameters[$wedding->id] ??= [
            'wedding' => $wedding->id,
            'step' => SetupStep::Couple->value,
            'household' => Household::factory()->for($wedding)->create()->id,
            'contentBlock' => ContentBlock::factory()->for($wedding)->create()->id,
            'inquiry' => Inquiry::factory()->create()->id,
            'photo' => GuestPhoto::factory()->for($wedding)->create()->id,
            'size' => 'original',
        ];
        $values = $this->parameters[$wedding->id];

        return url(preg_replace_callback('/\{(\w+)(?::\w+)?\}/', fn (array $match): string => (string) $values[$match[1]], $route->uri()));
    }
}
