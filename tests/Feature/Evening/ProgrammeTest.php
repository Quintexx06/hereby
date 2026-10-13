<?php

namespace Tests\Feature\Evening;

use App\Enums\Plan;
use App\Enums\ProgrammeStatus;
use App\Models\Household;
use App\Models\ProgrammeItem;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProgrammeTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_offers_a_speech_from_the_personal_link(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $household = Household::factory()->for($wedding)->create(['name' => 'Familie Meier']);

        $this->get(route('invitation.programme', $household))
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertInertia(fn (Assert $page) => $page
                ->component('invitation/Programme')
                ->where('contact', 'Familie Meier')
                ->has('translations.invitation.programme.title'));

        $this->post(route('invitation.programme.store', $household), [
            'kind' => 'speech', 'title' => 'Rede der Schwester', 'minutes' => 8, 'contact_name' => 'Heidi', 'notes' => 'Mikrofon',
        ])->assertRedirect(route('invitation.programme', $household));

        $item = ProgrammeItem::query()->sole();
        $this->assertSame($household->id, $item->household_id);
        $this->assertSame(ProgrammeStatus::Pending, $item->status);
        $this->assertSame(8, $item->minutes);
    }

    public function test_sign_ups_need_atelier_and_a_sensible_length(): void
    {
        $signature = Household::factory()->for(Wedding::factory()->create(['plan' => Plan::Signature]))->create();
        $this->get(route('invitation.programme', $signature))->assertNotFound();
        $this->post(route('invitation.programme.store', $signature), ['kind' => 'game', 'title' => 'Spiel', 'minutes' => 10, 'contact_name' => 'A'])->assertNotFound();

        $atelier = Household::factory()->for(Wedding::factory()->create(['plan' => Plan::Atelier]))->create();
        $this->post(route('invitation.programme.store', $atelier), ['kind' => 'game', 'title' => 'Spiel', 'minutes' => 90, 'contact_name' => 'A'])
            ->assertSessionHasErrors('minutes');
    }

    public function test_a_guest_withdraws_only_their_own_open_offer(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $household = Household::factory()->for($wedding)->create();
        $own = ProgrammeItem::factory()->for($wedding)->for($household)->create();
        $accepted = ProgrammeItem::factory()->for($wedding)->for($household)->create(['status' => ProgrammeStatus::Accepted]);
        $others = ProgrammeItem::factory()->for($wedding)->for(Household::factory()->for($wedding))->create();

        $this->delete(route('invitation.programme.destroy', [$household, $others]))->assertNotFound();
        $this->delete(route('invitation.programme.destroy', [$household, $accepted]))->assertForbidden();
        $this->delete(route('invitation.programme.destroy', [$household, $own]))->assertRedirect();

        $this->assertModelMissing($own);
        $this->assertModelExists($others);
    }

    public function test_the_couple_sees_only_counts_and_the_witness_link(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier, 'programme_minutes' => 45]);
        ProgrammeItem::factory()->for($wedding)->create(['title' => 'Geheimes Spiel', 'minutes' => 10, 'status' => ProgrammeStatus::Accepted]);
        ProgrammeItem::factory()->for($wedding)->create(['minutes' => 5]);

        $this->actingAs($wedding->owner)
            ->get(route('weddings.programme.edit', $wedding))
            ->assertInertia(fn (Assert $page) => $page
                ->component('evening/Programme')
                ->where('summary', ['total' => 2, 'accepted' => 1, 'minutes' => 10, 'budget' => 45])
                ->where('witnessLink', route('witness.show', $wedding->refresh()->witness_token)))
            ->assertDontSee('Geheimes Spiel');

        $this->actingAs($wedding->owner)
            ->put(route('weddings.programme.update', $wedding), ['programme_minutes' => 60])
            ->assertRedirect();
        $this->assertSame(60, $wedding->refresh()->programme_minutes);
    }

    public function test_the_witnesses_accept_order_and_print_the_running_order(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $first = ProgrammeItem::factory()->for($wedding)->create(['kind' => 'other', 'title' => 'Ansprache der Tante', 'minutes' => 8]);
        $second = ProgrammeItem::factory()->for($wedding)->create(['kind' => 'other', 'title' => 'Das Schuhquiz', 'minutes' => 15]);
        $declined = ProgrammeItem::factory()->for($wedding)->create(['kind' => 'other', 'title' => 'Fotoshow der Kindheit']);
        $link = route('witness.show', $wedding->witnessToken());

        $this->get($link)
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertInertia(fn (Assert $page) => $page->component('witness/Index')->has('programme', 3));

        $token = $wedding->witnessToken();
        $this->patch(route('witness.programme.update', [$token, $first]), ['status' => 'accepted'])->assertRedirect();
        $this->patch(route('witness.programme.update', [$token, $second]), ['status' => 'accepted'])->assertRedirect();
        $this->patch(route('witness.programme.update', [$token, $declined]), ['status' => 'declined'])->assertRedirect();
        $this->put(route('witness.programme.reorder', $token), ['ids' => [$second->id, $first->id, $declined->id]])->assertRedirect();

        $this->get(route('witness.programme.print', $token))
            ->assertOk()
            ->assertSeeInOrder(['Das Schuhquiz', 'Ansprache der Tante'])
            ->assertDontSee('Fotoshow der Kindheit');
    }

    public function test_the_witness_link_is_private_and_can_be_renewed(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $other = ProgrammeItem::factory()->for(Wedding::factory()->create(['plan' => Plan::Atelier]))->create();
        $old = $wedding->witnessToken();

        $this->patch(route('witness.programme.update', [$old, $other]), ['status' => 'accepted'])->assertNotFound();
        $this->get(route('witness.show', 'nicht-gueltig'))->assertNotFound();

        $this->actingAs($wedding->owner)->post(route('weddings.programme.renew', $wedding))->assertRedirect();

        $this->get(route('witness.show', $old))->assertNotFound();
        $this->get(route('witness.show', $wedding->refresh()->witnessToken()))->assertOk();
    }

    public function test_the_witness_link_closes_without_atelier(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $link = route('witness.show', $wedding->witnessToken());
        $wedding->forceFill(['plan' => Plan::Signature])->save();

        $this->get($link)->assertNotFound();
    }
}
