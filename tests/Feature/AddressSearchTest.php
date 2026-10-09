<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AddressSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_addresses_come_back_split_and_without_markup(): void
    {
        Http::fake(['api3.geo.admin.ch/*' => Http::response(['results' => [[
            'attrs' => [
                'label' => 'Seestrasse 1 <b>6354 Vitznau</b>',
                'lat' => 47.0105,
                'lon' => 8.4835,
                'featureId' => '190176817_0',
            ],
        ]]])]);

        $this->actingAs(User::factory()->create())
            ->getJson(route('addresses.search', ['q' => 'Seestrasse 1 Vitznau']))
            ->assertOk()
            ->assertExactJson(['results' => [[
                'label' => 'Seestrasse 1, 6354 Vitznau',
                'street' => 'Seestrasse 1',
                'postcode' => '6354',
                'town' => 'Vitznau',
                'lat' => 47.0105,
                'lng' => 8.4835,
                'reference' => '190176817_0',
            ]]]);
    }

    public function test_results_are_cached(): void
    {
        Http::fake(['api3.geo.admin.ch/*' => Http::response(['results' => []])]);
        $user = User::factory()->create();

        $this->actingAs($user)->getJson(route('addresses.search', ['q' => 'Bahnhofstrasse']));
        $this->actingAs($user)->getJson(route('addresses.search', ['q' => 'bahnhofstrasse']));

        Http::assertSentCount(1);
    }

    public function test_a_failing_service_returns_an_empty_list(): void
    {
        Http::fake(['api3.geo.admin.ch/*' => Http::response('down', 503)]);

        $this->actingAs(User::factory()->create())
            ->getJson(route('addresses.search', ['q' => 'Seestrasse']))
            ->assertOk()
            ->assertExactJson(['results' => []]);
    }

    public function test_short_queries_do_not_call_the_service(): void
    {
        Http::fake();

        $this->actingAs(User::factory()->create())->getJson(route('addresses.search', ['q' => 'Se']));

        Http::assertNothingSent();
    }
}
