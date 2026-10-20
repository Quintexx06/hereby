<?php

namespace Tests\Feature;

use App\Mail\PartnerInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_in_production_a_forged_host_never_reaches_the_app(): void
    {
        config(['app.url' => 'https://hereby.test']);
        $this->app->detectEnvironment(fn (): string => 'production');

        $this->get('https://evil.example/forgot-password')->assertNotFound();
        $this->get('https://hereby.test/forgot-password')->assertOk();
        $this->get('https://www.hereby.test/forgot-password')->assertOk();
        $this->get('http://localhost/up')->assertOk();
        $this->withHeader('X-Forwarded-Host', 'evil.example')->get('https://hereby.test/forgot-password')->assertNotFound();
    }

    public function test_every_response_forbids_framing_by_other_sites_and_sniffing(): void
    {
        $this->get('/')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_a_typed_name_cannot_become_a_link_in_a_mail(): void
    {
        $html = (new PartnerInvitation('[Konto bestätigen](https://evil.example/login)', 'https://hereby.test/zugang'))->render();

        $this->assertStringNotContainsString('href="https://evil.example', $html);
        $this->assertStringContainsString('href="https://hereby.test/zugang"', $html);
    }
}
