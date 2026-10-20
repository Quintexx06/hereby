<?php

namespace Tests\Feature\Evening;

use App\Enums\Plan;
use App\Mail\GroupPhotoCall;
use App\Models\GroupPhoto;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class GroupPhotosTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_couple_lists_the_groups_and_who_is_in_each(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $meier = Household::factory()->for($wedding)->create(['name' => 'Familie Meier']);
        $stranger = Household::factory()->for(Wedding::factory())->create();

        $this->actingAs($wedding->owner)
            ->put(route('weddings.group-photos.update', $wedding), ['groups' => [
                ['id' => null, 'title' => 'Familie Meier', 'place' => 'Beim Steg', 'household_ids' => [$meier->id]],
            ]])
            ->assertRedirect();

        $group = $wedding->groupPhotos()->sole();
        $this->assertSame('Beim Steg', $group->place);
        $this->assertSame([$meier->id], $group->households()->pluck('households.id')->all());

        $this->actingAs($wedding->owner)
            ->put(route('weddings.group-photos.update', $wedding), ['groups' => [
                ['id' => $group->id, 'title' => 'Turnverein', 'place' => null, 'household_ids' => [$stranger->id]],
            ]])
            ->assertSessionHasErrors('groups.0.household_ids.0');

        $this->actingAs($wedding->owner)
            ->get(route('weddings.group-photos.edit', $wedding))
            ->assertInertia(fn (Assert $page) => $page
                ->component('evening/GroupPhotos')
                ->where('groups.0.title', 'Familie Meier')
                ->where('households.0.name', 'Familie Meier'));

        $this->actingAs($wedding->owner)
            ->put(route('weddings.group-photos.update', $wedding), ['groups' => []])
            ->assertRedirect();
        $this->assertModelMissing($group);
    }

    public function test_the_witnesses_call_a_group_and_its_guests_are_told(): void
    {
        Mail::fake();
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $meier = Household::factory()->for($wedding)->create(['email' => 'meier@example.ch']);
        $other = Household::factory()->for($wedding)->create(['email' => 'other@example.ch']);
        $group = GroupPhoto::factory()->for($wedding)->create(['title' => 'Familie Meier', 'place' => 'Beim Steg']);
        $group->households()->attach($meier);

        $this->get(route('witness.show', $wedding->witnessToken()))
            ->assertInertia(fn (Assert $page) => $page->where('groups.0.title', 'Familie Meier'));

        $this->post(route('witness.group-photos.call', [$wedding->witnessToken(), $group]))->assertRedirect();

        $this->assertNotNull($group->refresh()->called_at);
        Mail::assertQueued(GroupPhotoCall::class, 1);
        Mail::assertQueued(GroupPhotoCall::class, fn (GroupPhotoCall $mail): bool => $mail->hasTo('meier@example.ch'));

        // Calling again right away renews the banner but mails nobody twice.
        $this->post(route('witness.group-photos.call', [$wedding->witnessToken(), $group]))->assertRedirect();
        Mail::assertQueued(GroupPhotoCall::class, 1);

        $this->get(route('invitation.show', $meier))
            ->assertInertia(fn (Assert $page) => $page->where('invitation.evening.call', ['title' => 'Familie Meier', 'place' => 'Beim Steg']));
        $this->get(route('invitation.show', $other))
            ->assertInertia(fn (Assert $page) => $page->where('invitation.evening.call', null));

        $this->travel(21)->minutes();
        $this->get(route('invitation.show', $meier))
            ->assertInertia(fn (Assert $page) => $page->where('invitation.evening.call', null));
    }

    public function test_a_witness_link_calls_only_its_own_groups(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $foreign = GroupPhoto::factory()->for(Wedding::factory()->create(['plan' => Plan::Atelier]))->create();

        $this->post(route('witness.group-photos.call', [$wedding->witnessToken(), $foreign]))->assertNotFound();
        $this->assertNull($foreign->refresh()->called_at);
    }
}
