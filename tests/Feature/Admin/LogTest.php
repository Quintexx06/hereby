<?php

namespace Tests\Feature\Admin;

use App\Mail\ErrorAlert;
use App\Models\LogEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use RuntimeException;
use Tests\TestCase;

class LogTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create(['email' => 'team@hereby.example']);
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }

    public function test_errors_are_counted_once_per_problem_and_mailed_at_most_hourly(): void
    {
        Mail::fake();
        $this->admin();

        foreach (range(1, 3) as $attempt) {
            Log::error('Boom', ['exception' => new RuntimeException('Zahlung fehlgeschlagen')]);
        }

        $entry = LogEntry::query()->sole();
        $this->assertSame(3, $entry->count);
        $this->assertSame('Zahlung fehlgeschlagen', $entry->message);
        Mail::assertSent(ErrorAlert::class, 1);

        $this->travel(61)->minutes();
        Log::error('Boom', ['exception' => new RuntimeException('Zahlung fehlgeschlagen')]);
        Mail::assertSent(ErrorAlert::class, 2);
    }

    public function test_warnings_are_kept_without_mail_and_debug_noise_is_ignored(): void
    {
        Mail::fake();
        $this->admin();

        Log::warning('Langsame Antwort');
        Log::info('Alles gut');

        $this->assertSame(['warning'], LogEntry::query()->pluck('level')->all());
        Mail::assertNothingSent();
    }

    public function test_personal_data_never_reaches_the_log(): void
    {
        Mail::fake();

        Log::warning('Antwort von anna.meier@example.ch, Telefon +41 79 123 45 67');

        $message = LogEntry::query()->sole()->message;
        $this->assertStringNotContainsString('anna.meier', $message);
        $this->assertStringNotContainsString('123 45 67', $message);
    }

    public function test_terminal_colour_codes_are_stripped_from_messages(): void
    {
        Mail::fake();

        Log::warning("\e[41m\e[37m SSR ERROR \e[0m window is not defined");

        $this->assertSame('SSR ERROR  window is not defined', trim(LogEntry::query()->sole()->message));
    }

    public function test_the_team_sees_and_resolves_entries_and_couples_cannot(): void
    {
        Mail::fake();
        Log::error('Kaputt');
        $entry = LogEntry::query()->sole();

        $this->actingAs($this->admin())->get(route('admin.log.index'))
            ->assertInertia(fn (Assert $page) => $page->component('admin/Log')->has('entries', 1)->where('adminIssues', 1));

        $this->patch(route('admin.log.update', $entry))->assertRedirect();
        $this->assertNotNull($entry->refresh()->resolved_at);

        Log::error('Kaputt');
        $this->assertNull($entry->refresh()->resolved_at, 'A problem that shows again opens again.');

        $couple = User::factory()->create();
        $this->actingAs($couple)->get(route('admin.log.index'))->assertForbidden();
    }
}
