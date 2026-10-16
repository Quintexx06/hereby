<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthMailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_verification_and_reset_mails_speak_swiss_german(): void
    {
        app()->setLocale('de_CH');
        $user = User::factory()->unverified()->create();

        $verify = (string) (new VerifyEmail)->toMail($user)->render();
        $reset = (string) (new ResetPassword('token'))->toMail($user)->render();

        $this->assertStringContainsString('E-Mail-Adresse bestätigen', $verify);
        $this->assertStringContainsString('Funktioniert der Knopf', $verify);
        $this->assertStringContainsString('Neues Passwort wählen', $reset);
        $this->assertStringNotContainsString('Regards', $verify.$reset);
        $this->assertStringNotContainsString('trouble clicking', $verify.$reset);
    }
}
