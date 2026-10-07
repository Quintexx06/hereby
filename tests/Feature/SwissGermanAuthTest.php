<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SwissGermanAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_failed_sign_in_explains_the_problem_in_swiss_german(): void
    {
        $this->post(route('login.store'), ['email' => 'anna@example.com', 'password' => 'falsch'])
            ->assertSessionHasErrors(['email' => __('auth.failed', [], 'de_CH')]);
    }

    public function test_sign_up_validation_speaks_swiss_german(): void
    {
        $this->post(route('register.store'), [])
            ->assertSessionHasErrors(['email' => 'E-Mail-Adresse wird benötigt.']);
    }
}
