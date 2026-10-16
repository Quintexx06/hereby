<?php

namespace Tests\Feature;

use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    public function test_a_missing_page_is_answered_in_swiss_german_with_a_way_home(): void
    {
        $this->get('/gibt-es-nicht')
            ->assertNotFound()
            ->assertSee('Diese Seite gibt es nicht.')
            ->assertSee('Zur Startseite')
            ->assertSee('<html lang="de-CH">', false)
            ->assertDontSee('Not Found');
    }

    public function test_every_error_page_has_its_copy_in_every_language(): void
    {
        foreach (['de_CH', 'fr', 'it', 'en'] as $locale) {
            foreach ([403, 404, 419, 429, 500, 503] as $code) {
                $this->assertNotSame("errors.{$code}.title", __("errors.{$code}.title", [], $locale), "{$code} in {$locale}");
            }
        }
    }
}
