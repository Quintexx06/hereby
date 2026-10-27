<?php

namespace Tests\Feature\Billing;

use App\Enums\Plan;
use App\Models\Household;
use App\Models\LogEntry;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublishTest extends TestCase
{
    use RefreshDatabase;

    private const string SECRET = 'whsec_test';

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.stripe.secret' => 'sk_test_123',
            'services.stripe.webhook_secret' => self::SECRET,
            'services.stripe.api_url' => 'https://stripe.test/v1',
            'services.stripe.products' => [],
        ]);
    }

    public function test_links_stay_closed_for_guests_until_a_plan_is_paid(): void
    {
        $wedding = Wedding::factory()->unpaid()->create();
        $household = Household::factory()->for($wedding)->create();

        $this->get(route('invitation.show', $household))->assertNotFound();
        $this->actingAs($wedding->owner)->get(route('invitation.show', $household))->assertOk();
    }

    public function test_the_paywall_offers_the_plans_with_what_is_due(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Signature]);

        $this->actingAs($wedding->owner)
            ->get(route('weddings.publish.show', $wedding))
            ->assertInertia(fn (Assert $page) => $page
                ->component('weddings/Publish')
                ->where('wedding.published', true)
                ->where('plans.0.owned', true)
                ->where('plans.1.key', 'atelier')
                ->where('plans.1.due', (Plan::Atelier->price() - Plan::Signature->price()) / 100)
            );
    }

    public function test_checkout_charges_the_plan_in_chf_and_sends_the_couple_to_stripe(): void
    {
        Http::fake(['stripe.test/*' => Http::response(['id' => 'cs_1', 'url' => 'https://checkout.stripe.test/cs_1'])]);
        $wedding = Wedding::factory()->unpaid()->create();

        $this->actingAs($wedding->owner)
            ->post(route('weddings.publish.store', $wedding), ['plan' => 'signature'], ['X-Inertia' => 'true'])
            ->assertStatus(409)
            ->assertHeader('X-Inertia-Location', 'https://checkout.stripe.test/cs_1');

        Http::assertSent(fn (ClientRequest $request): bool => $request['line_items'][0]['price_data']['currency'] === 'chf'
            && (int) $request['line_items'][0]['price_data']['unit_amount'] === Plan::Signature->price()
            && $request['metadata']['wedding_id'] == $wedding->id
            && $request['invoice_creation']['enabled'] === 'true'
            && $request['managed_payments']['enabled'] === 'false'
            && $request['allowed_payment_method_types'] === ['card']
            && ! isset($request['payment_method_types']));
    }

    public function test_an_older_pinned_stripe_version_still_gets_the_old_payment_methods_parameter(): void
    {
        Http::fake(['stripe.test/*' => Http::response(['id' => 'cs_1', 'url' => 'https://checkout.stripe.test/cs_1'])]);
        config(['services.stripe.api_version' => '2026-04-22.dahlia']);
        $wedding = Wedding::factory()->unpaid()->create();

        $this->actingAs($wedding->owner)
            ->post(route('weddings.publish.store', $wedding), ['plan' => 'signature'], ['X-Inertia' => 'true'])
            ->assertStatus(409);

        Http::assertSent(fn (ClientRequest $request): bool => $request['payment_method_types'] === ['card']
            && ! isset($request['allowed_payment_method_types'])
            && $request->header('Stripe-Version') === ['2026-04-22.dahlia']);
    }

    public function test_checkout_bills_the_stripe_product_of_the_plan_when_one_is_configured(): void
    {
        Http::fake(['stripe.test/*' => Http::response(['id' => 'cs_1', 'url' => 'https://checkout.stripe.test/cs_1'])]);
        config(['services.stripe.products.atelier' => 'prod_atelier']);
        $wedding = Wedding::factory()->create(['plan' => Plan::Signature]);

        $this->actingAs($wedding->owner)
            ->post(route('weddings.publish.store', $wedding), ['plan' => 'atelier'], ['X-Inertia' => 'true'])
            ->assertStatus(409);

        Http::assertSent(fn (ClientRequest $request): bool => $request['line_items'][0]['price_data']['product'] === 'prod_atelier'
            && ! isset($request['line_items'][0]['price_data']['product_data'])
            && (int) $request['line_items'][0]['price_data']['unit_amount'] === Plan::Atelier->price() - Plan::Signature->price());
    }

    public function test_checkout_survives_a_stripe_hiccup_without_opening_a_second_session(): void
    {
        Http::fake(['stripe.test/*' => Http::sequence()
            ->pushStatus(503)
            ->push(['id' => 'cs_1', 'url' => 'https://checkout.stripe.test/cs_1'])]);
        $wedding = Wedding::factory()->unpaid()->create();

        $this->actingAs($wedding->owner)
            ->post(route('weddings.publish.store', $wedding), ['plan' => 'signature'], ['X-Inertia' => 'true'])
            ->assertHeader('X-Inertia-Location', 'https://checkout.stripe.test/cs_1');

        $keys = Http::recorded()->map(fn (array $pair): string => $pair[0]->header('Idempotency-Key')[0] ?? '');

        $this->assertCount(2, $keys);
        $this->assertNotSame('', $keys[0]);
        $this->assertSame($keys[0], $keys[1]);
    }

    public function test_a_stripe_refusal_reaches_the_team_log_and_not_the_couple_as_an_error_page(): void
    {
        Http::fake(['stripe.test/*' => Http::response(['error' => [
            'type' => 'invalid_request_error',
            'message' => 'The payment method type provided: twint is invalid.',
        ]], 400)]);
        $wedding = Wedding::factory()->unpaid()->create();

        $this->actingAs($wedding->owner)
            ->from(route('weddings.publish.show', $wedding))
            ->post(route('weddings.publish.store', $wedding), ['plan' => 'signature'])
            ->assertRedirect(route('weddings.publish.show', $wedding));

        $this->assertStringContainsString('twint is invalid', LogEntry::query()->sole()->message);
        $this->assertNull($wedding->fresh()->paid_at);
    }

    public function test_a_plan_already_owned_cannot_be_bought_again_and_couture_is_not_sold_online(): void
    {
        Http::fake();
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);

        $this->actingAs($wedding->owner)
            ->post(route('weddings.publish.store', $wedding), ['plan' => 'signature'])
            ->assertSessionHasErrors('plan');
        $this->actingAs($wedding->owner)
            ->post(route('weddings.publish.store', $wedding), ['plan' => 'couture'])
            ->assertSessionHasErrors('plan');
        Http::assertNothingSent();
    }

    public function test_only_the_couple_reaches_their_paywall(): void
    {
        $wedding = Wedding::factory()->unpaid()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('weddings.publish.show', $wedding))
            ->assertForbidden();
    }

    public function test_a_signed_webhook_unlocks_the_plan(): void
    {
        $wedding = Wedding::factory()->unpaid()->create();

        $this->webhook($this->event($wedding, 'atelier'))->assertNoContent();

        $wedding->refresh();
        $this->assertSame(Plan::Atelier, $wedding->plan);
        $this->assertTrue($wedding->isPublished());
        $this->assertSame('pi_1', $wedding->stripe_payment_intent);
    }

    public function test_the_webhook_is_exempt_from_the_csrf_check_stripe_cannot_pass(): void
    {
        $route = Route::getRoutes()->getByName('stripe.webhook');

        $this->assertNotNull($route);
        $this->assertContains(PreventRequestForgery::class, $route->excludedMiddleware());
    }

    public function test_an_unsigned_or_unpaid_webhook_changes_nothing(): void
    {
        $wedding = Wedding::factory()->unpaid()->create();

        $this->webhook($this->event($wedding, 'atelier'), 'wrong_secret')->assertStatus(400);
        $this->webhook($this->event($wedding, 'atelier', 'unpaid'))->assertNoContent();

        $this->assertFalse($wedding->refresh()->isPublished());
    }

    public function test_a_payment_in_another_currency_unlocks_nothing(): void
    {
        $wedding = Wedding::factory()->unpaid()->create();
        $payload = (string) json_encode([
            'type' => 'checkout.session.completed',
            'data' => ['object' => ['currency' => 'eur'] + $this->checkoutSession($wedding, 'atelier')],
        ]);

        $this->webhook($payload)->assertNoContent();

        $this->assertFalse($wedding->refresh()->isPublished());
    }

    public function test_returning_from_stripe_confirms_the_session_at_stripe(): void
    {
        $wedding = Wedding::factory()->unpaid()->create();
        Http::fake(['stripe.test/v1/checkout/sessions/cs_1' => Http::response($this->checkoutSession($wedding, 'signature'))]);

        $this->actingAs($wedding->owner)
            ->get(route('weddings.publish.return', $wedding).'?session_id=cs_1')
            ->assertRedirect(route('weddings.guests.index', $wedding));

        $this->assertSame(Plan::Signature, $wedding->refresh()->plan);
    }

    public function test_a_session_for_another_wedding_unlocks_nothing(): void
    {
        $wedding = Wedding::factory()->unpaid()->create();
        $other = Wedding::factory()->unpaid()->create();
        Http::fake(['stripe.test/*' => Http::response($this->checkoutSession($other, 'atelier'))]);

        $this->actingAs($wedding->owner)
            ->get(route('weddings.publish.return', $wedding).'?session_id=cs_other')
            ->assertRedirect(route('weddings.publish.show', $wedding));

        $this->assertFalse($wedding->refresh()->isPublished());
    }

    public function test_paying_never_downgrades_a_plan(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);

        $this->webhook($this->event($wedding, 'signature'))->assertNoContent();

        $this->assertSame(Plan::Atelier, $wedding->refresh()->plan);
    }

    public function test_the_team_unlocks_a_plan_paid_by_invoice(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $wedding = Wedding::factory()->unpaid()->create();

        $this->actingAs($admin)
            ->patch(route('admin.weddings.unlock', $wedding), ['plan' => 'couture'])
            ->assertRedirect();

        $this->assertSame(Plan::Couture, $wedding->refresh()->plan);
        $this->actingAs($wedding->owner)
            ->patch(route('admin.weddings.unlock', $wedding), ['plan' => 'couture'])
            ->assertForbidden();
    }

    /**
     * @return array<string, mixed>
     */
    private function checkoutSession(Wedding $wedding, string $plan, string $status = 'paid'): array
    {
        return [
            'id' => 'cs_1',
            'object' => 'checkout.session',
            'payment_status' => $status,
            'currency' => 'chf',
            'payment_intent' => 'pi_1',
            'metadata' => ['wedding_id' => (string) $wedding->id, 'plan' => $plan],
        ];
    }

    private function event(Wedding $wedding, string $plan, string $status = 'paid'): string
    {
        return (string) json_encode([
            'type' => 'checkout.session.completed',
            'data' => ['object' => $this->checkoutSession($wedding, $plan, $status)],
        ]);
    }

    private function webhook(string $payload, string $secret = self::SECRET): TestResponse
    {
        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

        return $this->call('POST', route('stripe.webhook'), [], [], [], [
            'HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$signature}",
            'CONTENT_TYPE' => 'application/json',
        ], $payload);
    }
}
