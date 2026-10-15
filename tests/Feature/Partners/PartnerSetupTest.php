<?php

namespace Tests\Feature\Partners;

use App\Enums\ContentBlockType;
use App\Mail\PartnerInvitation;
use App\Models\Partner;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PartnerSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_team_opens_a_partner_account_and_marks_a_venue_as_showcase(): void
    {
        Mail::fake();
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin)
            ->post(route('admin.partners.store'), ['type' => 'venue', 'name' => 'Seehotel Kastanienbaum', 'email' => 'Events@Seehotel.ch'])
            ->assertRedirect();

        $partner = Partner::query()->sole();
        $this->assertMatchesRegularExpression('/^[A-Z2-9]{4}-[A-Z2-9]{4}$/', $partner->code);
        $this->assertSame($partner->id, User::query()->where('email', 'events@seehotel.ch')->sole()->partner_id);
        Mail::assertQueued(PartnerInvitation::class, fn (PartnerInvitation $mail): bool => $mail->hasTo('events@seehotel.ch'));

        $this->actingAs($admin)->patch(route('admin.partners.update', $partner), ['is_showcase' => true])->assertRedirect();
        $this->assertTrue($partner->refresh()->is_showcase);
    }

    public function test_couples_cannot_open_partner_accounts(): void
    {
        $couple = Wedding::factory()->create()->owner;

        $this->actingAs($couple)
            ->post(route('admin.partners.store'), ['type' => 'venue', 'name' => 'X', 'email' => 'x@example.ch'])
            ->assertForbidden();
    }

    public function test_a_couple_connects_a_venue_by_code_and_its_profile_fills_what_is_empty(): void
    {
        $venue = Partner::factory()->create(['name' => 'Seehotel Kastanienbaum', 'travel' => 'Bus 21 ab Luzern.', 'stay' => '42 Zimmer.']);
        $wedding = Wedding::factory()->create(['venue_name' => null]);
        $wedding->contentBlocks()->create(['type' => ContentBlockType::Stay, 'position' => 1, 'content' => ['de_CH' => ['body' => 'Unser eigener Text.']]]);

        $this->actingAs($wedding->owner)
            ->post(route('weddings.partners.store', $wedding), ['code' => strtolower(str_replace('-', ' ', $venue->code))])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $wedding->refresh();
        $this->assertTrue($wedding->partners->contains($venue));
        $this->assertSame('Seehotel Kastanienbaum', $wedding->venue_name);
        $this->assertSame('Bus 21 ab Luzern.', $wedding->contentBlocks()->where('type', 'travel')->sole()->content['de_CH']['body']);
        $this->assertSame('Unser eigener Text.', $wedding->contentBlocks()->where('type', 'stay')->sole()->content['de_CH']['body']);
    }

    public function test_a_venue_never_overwrites_the_couples_own_venue(): void
    {
        $venue = Partner::factory()->create();
        $wedding = Wedding::factory()->create(['venue_name' => 'Villa am See']);

        $this->actingAs($wedding->owner)->post(route('weddings.partners.store', $wedding), ['code' => $venue->code]);

        $this->assertSame('Villa am See', $wedding->refresh()->venue_name);
    }

    public function test_an_unknown_code_is_refused_and_a_partner_can_be_let_go(): void
    {
        $photographer = Partner::factory()->photographer()->create();
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->post(route('weddings.partners.store', $wedding), ['code' => 'NOPE-NOPE'])
            ->assertSessionHasErrors('code');

        $this->actingAs($wedding->owner)->post(route('weddings.partners.store', $wedding), ['code' => $photographer->code]);
        $this->actingAs($wedding->owner)->delete(route('weddings.partners.destroy', [$wedding, $photographer]))->assertRedirect();

        $this->assertCount(0, $wedding->partners()->get());
    }
}
