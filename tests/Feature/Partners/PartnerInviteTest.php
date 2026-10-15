<?php

namespace Tests\Feature\Partners;

use App\Mail\PartnerJoinInvitation;
use App\Models\Partner;
use App\Models\PartnerInvite;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PartnerInviteTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_couple_invites_a_partner_who_joins_already_connected(): void
    {
        Mail::fake();
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->post(route('weddings.partners.invites.store', $wedding), ['type' => 'photographer', 'name' => 'Studio Licht', 'email' => 'Hallo@Studio-Licht.ch'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $invite = PartnerInvite::query()->sole();
        Mail::assertQueued(PartnerJoinInvitation::class, fn (PartnerJoinInvitation $mail): bool => $mail->hasTo('hallo@studio-licht.ch'));

        auth()->logout();
        $this->get(route('partner-join.show', $invite->token))
            ->assertInertia(fn (Assert $page) => $page->component('auth/PartnerJoin')->where('couple', $wedding->couple_names));

        $this->post(route('partner-join.store', $invite->token), ['name' => 'Studio Licht GmbH', 'password' => 'ein-langes-passwort', 'password_confirmation' => 'ein-langes-passwort'])
            ->assertRedirect(route('partner.calendar'));

        $partner = Partner::query()->sole();
        $this->assertSame('Studio Licht GmbH', $partner->name);
        $this->assertTrue($wedding->partners()->whereKey($partner->id)->exists());
        $this->assertAuthenticatedAs(User::query()->where('email', 'hallo@studio-licht.ch')->sole());
        $this->assertDatabaseCount('partner_invites', 0);
    }

    public function test_a_partner_already_on_hereby_is_connected_without_an_invite(): void
    {
        Mail::fake();
        $venue = Partner::factory()->create();
        $login = User::factory()->create(['email' => 'events@seehotel.ch']);
        $login->partner_id = $venue->id;
        $login->save();
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->post(route('weddings.partners.invites.store', $wedding), ['type' => 'venue', 'name' => 'Seehotel', 'email' => 'events@seehotel.ch']);

        $this->assertTrue($wedding->partners()->whereKey($venue->id)->exists());
        Mail::assertNothingQueued();
    }

    public function test_an_old_or_unknown_invitation_link_does_not_open(): void
    {
        $invite = PartnerInvite::factory()->create();
        $this->travel(PartnerInvite::DAYS_VALID + 1)->days();

        $this->get(route('partner-join.show', $invite->token))->assertNotFound();
        $this->get(route('partner-join.show', 'unknown'))->assertNotFound();
    }

    public function test_a_partners_link_brings_the_couple_to_a_one_click_connect(): void
    {
        $venue = Partner::factory()->create();
        $wedding = Wedding::factory()->create();

        $this->get(route('partners.connect', $venue->code))->assertRedirect(route('login'));

        $this->actingAs($wedding->owner)
            ->get(route('partners.connect', $venue->code))
            ->assertRedirect(route('weddings.partners.index', ['wedding' => $wedding, 'code' => $venue->code]));

        $this->get(route('weddings.partners.index', ['wedding' => $wedding, 'code' => $venue->code]))
            ->assertInertia(fn (Assert $page) => $page->where('suggested.name', $venue->name));
    }

    public function test_the_team_corrects_a_partners_details_and_sign_in(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $partner = Partner::factory()->photographer()->create();
        $login = User::factory()->create();
        $login->partner_id = $partner->id;
        $login->save();

        $this->actingAs($admin)
            ->patch(route('admin.partners.update', $partner), ['name' => 'Neuer Name', 'type' => 'planner', 'login' => 'Neu@Example.ch', 'town' => 'Luzern', 'is_showcase' => true])
            ->assertSessionHasNoErrors();

        $partner->refresh();
        $this->assertSame('Neuer Name', $partner->name);
        $this->assertSame('planner', $partner->type->value);
        $this->assertSame('Luzern', $partner->town);
        $this->assertFalse($partner->is_showcase, 'Showcase is for venues only.');
        $this->assertSame('neu@example.ch', $login->refresh()->email);
    }
}
