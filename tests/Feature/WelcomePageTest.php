<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_in_swiss_german_with_seo_and_faq(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->where('locale', 'de_CH')
                ->where('seo.title', __('landing.seo.title', [], 'de_CH'))
                ->has('faq', count(__('landing.faq', [], 'de_CH')))
                ->has('faq.0', fn (Assert $item) => $item->hasAll(['question', 'answer']))
            );
    }

    public function test_search_engines_get_meta_tags_and_faq_structured_data_without_javascript(): void
    {
        $html = $this->get(route('home'))->assertOk()->getContent();

        $this->assertStringContainsString('<html lang="de-CH"', $html);
        $this->assertStringContainsString('<meta data-inertia="description" name="description"', $html);
        $this->assertStringContainsString('rel="canonical" href="'.route('home').'"', $html);
        $this->assertStringContainsString('property="og:image"', $html);
        $this->assertStringContainsString('"@type":"FAQPage"', $html);
        $this->assertStringContainsString(e(__('landing.faq.0.question', [], 'de_CH')), $html);
    }

    public function test_other_pages_do_not_carry_the_landing_structured_data(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertDontSee('FAQPage', escape: false);
    }
}
