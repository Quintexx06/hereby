<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_wrong_password_on_confirm_is_answered_in_swiss_german(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('password.confirm.store'), ['password' => 'falsch'])
            ->assertSessionHasErrors(['password' => 'Das Passwort stimmt nicht. Bitte versucht es noch einmal.']);
    }

    /**
     * Every rule Laravel knows has a message in every language people see,
     * so nothing falls back to English.
     */
    public function test_every_validation_rule_is_translated(): void
    {
        $english = $this->keys(require base_path('vendor/laravel/framework/src/Illuminate/Translation/lang/en/validation.php'));

        foreach (['de_CH', 'fr', 'it'] as $locale) {
            $translated = $this->keys(require lang_path("{$locale}/validation.php"));

            $this->assertSame([], array_values(array_diff($english, $translated)), "Missing in {$locale}.");
        }
    }

    /**
     * @param  array<string, mixed>  $lines
     * @return list<string>
     */
    private function keys(array $lines, string $prefix = ''): array
    {
        $keys = [];

        foreach ($lines as $key => $line) {
            if (in_array($key, ['custom', 'attributes'], true)) {
                continue;
            }

            $keys = [...$keys, ...(is_array($line) ? $this->keys($line, "{$prefix}{$key}.") : ["{$prefix}{$key}"])];
        }

        return $keys;
    }
}
