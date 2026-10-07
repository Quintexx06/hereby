<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_policy_and_imprint_are_public_and_in_swiss_german(): void
    {
        $this->get(route('legal.privacy'))
            ->assertOk()
            ->assertSee('<html lang="de-CH"', escape: false)
            ->assertInertia(fn (Assert $page) => $page->component('legal/Privacy'));

        $this->get(route('legal.imprint'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('legal/Imprint'));
    }
}
