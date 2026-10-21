<?php

namespace Tests\Feature\Marketing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SampleSitesTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_template_has_a_public_sample_site(): void
    {
        foreach (['atelier', 'garten', 'panorama'] as $template) {
            $this->get("/beispiel/{$template}")
                ->assertOk()
                ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
                ->assertInertia(fn (Assert $page) => $page->component('invitation/Show')
                    ->where('invitation.wedding.template', $template)
                    ->where('invitation.wedding.coupleNames', 'Anna & Luca')
                    ->where('demo.sample', true)
                    ->where('demo.links.garten', route('samples.show', 'garten'))
                    ->where('invitation.links.reply', route('samples.reply', $template))
                    ->has('translations.invitation'));
        }

        $this->get('/beispiel/barock')->assertNotFound();
    }

    public function test_a_visitor_replies_on_a_sample_and_nothing_is_stored(): void
    {
        $this->get(route('samples.reply', 'garten'))->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('invitation/Reply')
                ->where('reply.links.reply', route('samples.reply.update', 'garten')));

        $this->followingRedirects()
            ->put(route('samples.reply.update', 'garten'), ['answers' => [['event_id' => 1, 'status' => 'attending']]])
            ->assertInertia(fn (Assert $page) => $page->where('replied', true)->where('invitation.reply.answered', true));

        $this->assertDatabaseCount('households', 0);
        $this->assertDatabaseCount('partners', 0);
        $this->get(route('samples.calendar', 'panorama'))->assertOk()->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
    }
}
