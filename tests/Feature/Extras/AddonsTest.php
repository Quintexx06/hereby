<?php

namespace Tests\Feature\Extras;

use App\Enums\Addon;
use App\Enums\Plan;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AddonsTest extends TestCase
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

    public function test_the_extras_page_offers_what_the_plan_does_not_include(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);

        $this->actingAs($wedding->owner)
            ->get(route('weddings.extras.show', $wedding))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('extras/Index')
                ->where('wedding.evening', true)
                ->where('addons.0.key', 'newspaper')
                ->where('addons.0.owned', false)
                ->where('addons.0.buyable', true)
                ->where('addons.0.price', Addon::Newspaper->price() / 100));
    }

    public function test_couture_includes_the_newspaper_the_chase_and_the_domain(): void
    {
        $couture = Wedding::factory()->create(['plan' => Plan::Couture]);
        $signature = Wedding::factory()->create(['plan' => Plan::Signature]);

        $this->assertTrue($couture->hasAddon(Addon::Newspaper));
        $this->assertTrue($couture->hasAddon(Addon::ChaseService));
        $this->assertTrue($couture->hasAddon(Addon::CustomDomain));
        $this->assertFalse($signature->hasAddon(Addon::Newspaper));
        $this->assertTrue($couture->includesEvening());
        $this->assertFalse($signature->includesEvening());
    }

    public function test_buying_an_addon_charges_its_price_at_stripe(): void
    {
        Http::fake(['stripe.test/*' => Http::response(['id' => 'cs_1', 'url' => 'https://checkout.stripe.test/cs_1'])]);
        $wedding = Wedding::factory()->create(['plan' => Plan::Signature]);

        $this->actingAs($wedding->owner)
            ->post(route('weddings.extras.store', $wedding), ['addon' => 'newspaper'], ['X-Inertia' => 'true'])
            ->assertStatus(409)
            ->assertHeader('X-Inertia-Location', 'https://checkout.stripe.test/cs_1');

        Http::assertSent(fn (ClientRequest $request): bool => (int) $request['line_items'][0]['price_data']['unit_amount'] === Addon::Newspaper->price()
            && $request['metadata']['addon'] === 'newspaper'
            && ! isset($request['metadata']['plan']));
    }

    public function test_an_addon_needs_a_paid_plan_and_is_bought_once(): void
    {
        $unpaid = Wedding::factory()->unpaid()->create();
        $this->actingAs($unpaid->owner)
            ->post(route('weddings.extras.store', $unpaid), ['addon' => 'newspaper'])
            ->assertSessionHasErrors('addon');

        $couture = Wedding::factory()->create(['plan' => Plan::Couture]);
        $this->actingAs($couture->owner)
            ->post(route('weddings.extras.store', $couture), ['addon' => 'custom_domain'])
            ->assertSessionHasErrors('addon');

        $signature = Wedding::factory()->create(['plan' => Plan::Signature]);
        $this->actingAs($signature->owner)
            ->post(route('weddings.extras.store', $signature), ['addon' => 'keep_memories'])
            ->assertSessionHasErrors('addon');
    }

    public function test_a_signed_webhook_records_the_addon_once(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Signature]);

        $this->webhook($this->event($wedding, 'chase_service'))->assertNoContent();
        $this->webhook($this->event($wedding, 'chase_service'))->assertNoContent();

        $this->assertTrue($wedding->refresh()->hasAddon(Addon::ChaseService));
        $this->assertSame(1, $wedding->addons()->count());
        $this->assertSame(Plan::Signature, $wedding->plan);
    }

    public function test_keeping_the_memories_adds_a_year_per_purchase(): void
    {
        $this->travelTo('2027-01-10');
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier, 'wedding_date' => '2026-06-20']);

        $this->webhook($this->event($wedding, 'keep_memories', 'cs_a'))->assertNoContent();
        $this->assertSame('2028-06-20', $wedding->refresh()->memories_kept_until?->toDateString());

        $this->webhook($this->event($wedding, 'keep_memories', 'cs_a'))->assertNoContent();
        $this->webhook($this->event($wedding, 'keep_memories', 'cs_b'))->assertNoContent();
        $this->assertSame('2029-06-20', $wedding->refresh()->memories_kept_until?->toDateString());
    }

    public function test_only_the_couple_reaches_their_extras(): void
    {
        $wedding = Wedding::factory()->create();
        $other = Wedding::factory()->create();

        $this->actingAs($other->owner)->get(route('weddings.extras.show', $wedding))->assertForbidden();
    }

    private function event(Wedding $wedding, string $addon, string $session = 'cs_1'): string
    {
        return (string) json_encode([
            'type' => 'checkout.session.completed',
            'data' => ['object' => [
                'id' => $session,
                'object' => 'checkout.session',
                'payment_status' => 'paid',
                'currency' => 'chf',
                'payment_intent' => 'pi_'.$session,
                'metadata' => ['wedding_id' => (string) $wedding->id, 'addon' => $addon],
            ]],
        ]);
    }

    private function webhook(string $payload): TestResponse
    {
        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, self::SECRET);

        return $this->call('POST', route('stripe.webhook'), [], [], [], [
            'HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$signature}",
            'CONTENT_TYPE' => 'application/json',
        ], $payload);
    }
}
