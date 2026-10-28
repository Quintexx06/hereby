<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Support\WelcomeLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class WelcomeLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_welcome_link_leads_to_choosing_a_first_password(): void
    {
        $user = User::factory()->create();

        $this->get(WelcomeLink::to($user))
            ->assertRedirectContains('/reset-password/')
            ->assertRedirectContains('willkommen=1');
    }

    public function test_choosing_the_first_password_signs_in_straight_away(): void
    {
        $user = User::factory()->create();

        $this->post(route('password.update'), [
            'token' => Password::createToken($user),
            'email' => $user->email,
            'password' => 'unser-erstes-passwort',
            'password_confirmation' => 'unser-erstes-passwort',
            'welcome' => true,
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_an_ordinary_reset_still_ends_at_the_login(): void
    {
        $user = User::factory()->create();

        $this->post(route('password.update'), [
            'token' => Password::createToken($user),
            'email' => $user->email,
            'password' => 'ein-neues-passwort',
            'password_confirmation' => 'ein-neues-passwort',
        ])->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_the_welcome_link_stops_working_once_a_password_is_chosen(): void
    {
        $user = User::factory()->create();
        $link = WelcomeLink::to($user);

        $user->forceFill(['password' => 'unser-erstes-passwort'])->save();

        $this->get($link)
            ->assertRedirect(route('login'))
            ->assertSessionHas('status');
    }
}
