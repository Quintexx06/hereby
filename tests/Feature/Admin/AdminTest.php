<?php

namespace Tests\Feature\Admin;

use App\Mail\CoupleInvitation;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }

    public function test_only_admins_reach_the_admin_area(): void
    {
        $this->get(route('admin.weddings.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('admin.weddings.index'))->assertForbidden();
    }

    public function test_admin_sees_every_wedding(): void
    {
        Wedding::factory()->count(2)->create();

        $this->actingAs($this->admin())
            ->get(route('admin.weddings.index'))
            ->assertInertia(fn (Assert $page) => $page->component('admin/Weddings')->has('weddings', 2));
    }

    public function test_admin_sets_up_a_couple_who_gets_a_link_to_choose_a_password(): void
    {
        Mail::fake();

        $this->actingAs($this->admin())
            ->post(route('admin.weddings.store'), [
                'email' => 'anna@example.ch',
                'partner_one' => 'Anna',
                'partner_two' => 'Luca',
            ])
            ->assertRedirect();

        $couple = User::query()->where('email', 'anna@example.ch')->sole();
        $wedding = $couple->weddings()->sole();
        $this->assertSame('Anna & Luca', $wedding->couple_names);
        $this->assertTrue($wedding->isDraft());
        $this->assertNotNull($couple->email_verified_at);
        Mail::assertQueued(CoupleInvitation::class, fn (CoupleInvitation $mail): bool => $mail->hasTo('anna@example.ch')
            && str_contains($mail->link, '/reset-password/'));
    }

    public function test_admin_works_on_a_couples_wedding_with_the_couples_pages(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs($this->admin())->get(route('weddings.guests.index', $wedding))->assertOk();
        $this->actingAs(User::factory()->create())->get(route('weddings.guests.index', $wedding))->assertForbidden();
    }

    public function test_nobody_makes_themselves_admin_through_a_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Anna',
            'email' => $user->email,
            'is_admin' => true,
        ]);

        $this->assertFalse($user->fresh()->is_admin);
    }

    public function test_command_makes_an_admin(): void
    {
        $user = User::factory()->create(['email' => 'team@hereby.ch']);

        $this->artisan('hereby:make-admin', ['email' => 'team@hereby.ch'])->assertSuccessful();

        $this->assertTrue($user->fresh()->is_admin);
    }
}
