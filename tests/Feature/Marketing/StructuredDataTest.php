<?php

namespace Tests\Feature\Marketing;

use App\Http\Controllers\Marketing\TopicPageController;
use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StructuredDataTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array<string, mixed>> The graph's nodes by @type.
     */
    private function graph(string $url): array
    {
        $html = $this->get($url)->assertOk()->getContent() ?: '';
        preg_match('#<script type="application/ld\+json">(.+?)</script>#s', $html, $match);
        $data = json_decode($match[1] ?? '', true);
        $this->assertIsArray($data, "No JSON-LD on {$url}.");

        return collect($data['@graph'])->keyBy('@type')->all();
    }

    public function test_the_landing_page_describes_organization_site_and_offers_in_chf(): void
    {
        $graph = $this->graph(route('home'));

        $this->assertArrayHasKey('Organization', $graph);
        $this->assertArrayHasKey('WebSite', $graph);
        $this->assertArrayHasKey('FAQPage', $graph);
        $offers = $graph['SoftwareApplication']['offers'];
        $this->assertSame('CHF', $offers['priceCurrency']);
        $this->assertSame(config('hereby.plans.signature') / 100, $offers['lowPrice']);
        $this->assertCount(3, $offers['offers']);
    }

    public function test_topic_pages_carry_a_breadcrumb_and_their_faq(): void
    {
        $graph = $this->graph(route('topics.show', 'hochzeit-rsvp-online'));

        $this->assertSame(route('topics.show', 'hochzeit-rsvp-online'), $graph['BreadcrumbList']['itemListElement'][1]['item']);
        $this->assertSame(__('pages.hochzeit-rsvp-online.faq.0.question', [], 'de_CH'), $graph['FAQPage']['mainEntity'][0]['name']);
    }

    public function test_a_showcase_venue_is_an_event_venue_with_its_address(): void
    {
        $venue = Partner::factory()->create(['is_showcase' => true, 'name' => 'Seehotel', 'address' => 'St. Niklausenstrasse 105', 'postcode' => '6047', 'town' => 'Kastanienbaum', 'capacity' => 120]);

        $graph = $this->graph(route('venues.show', $venue->slug));

        $this->assertSame('6047', $graph['EventVenue']['address']['postalCode']);
        $this->assertSame(120, $graph['EventVenue']['maximumAttendeeCapacity']);
        $this->assertMatchesRegularExpression('#<title[^>]*>Seehotel – Hochzeitslocation in Kastanienbaum - '.preg_quote((string) config('app.name')).'</title>#u', $this->get(route('venues.show', $venue->slug))->getContent() ?: '');
    }

    public function test_titles_and_descriptions_fit_the_search_results(): void
    {
        $pages = [__('landing.seo', [], 'de_CH'), ...array_map(fn (string $slug): array => __("pages.{$slug}", [], 'de_CH'), TopicPageController::slugs())];

        foreach ($pages as $page) {
            $this->assertLessThanOrEqual(60, mb_strlen($page['title']), $page['title']);
            $this->assertLessThanOrEqual(160, mb_strlen($page['description']), $page['description']);
        }
    }

    public function test_app_and_sign_in_pages_stay_out_of_search(): void
    {
        $this->get(route('login'))
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
            ->assertDontSee('application/ld+json', false);

        $this->get(route('home'))->assertDontSee('noindex', false);
    }
}
