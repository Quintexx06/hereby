<?php

namespace Tests\Feature\Marketing;

use App\Http\Controllers\Marketing\TopicPageController;
use App\Models\Partner;
use App\Support\Chf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SeoPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_topic_page_renders_with_its_own_meta_and_faq_data(): void
    {
        foreach (TopicPageController::slugs() as $slug) {
            $response = $this->get(route('topics.show', $slug));

            $response->assertOk()
                ->assertSee('<link data-inertia="canonical" rel="canonical" href="'.route('topics.show', $slug).'">', false)
                ->assertSee('"@type":"FAQPage"', false)
                ->assertInertia(fn (Assert $page) => $page->component('topics/Show')->has('faq')->has('related', 3));
        }
    }

    public function test_the_price_page_quotes_the_configured_prices(): void
    {
        $this->get(route('topics.show', 'preise'))
            ->assertSee(Chf::price('plans.signature'))
            ->assertSee(Chf::price('plans.atelier'));
    }

    public function test_the_sitemap_lists_public_pages_only(): void
    {
        $venue = Partner::factory()->create(['is_showcase' => true]);
        $hidden = Partner::factory()->create(['is_showcase' => false]);

        $response = $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<loc>'.route('home').'</loc>', false)
            ->assertSee('<loc>'.route('topics.show', 'preise').'</loc>', false)
            ->assertSee(route('venues.show', $venue->slug), false)
            ->assertDontSee(route('venues.show', $hidden->slug), false)
            ->assertDontSee('/hochzeit/', false);

        $this->assertNotFalse(simplexml_load_string($response->getContent() ?: ''), 'The sitemap is valid XML.');
    }

    public function test_robots_announces_the_sitemap_and_keeps_the_app_out(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: '.route('sitemap'))
            ->assertSee('Disallow: /i/');
    }
}
