<?php

namespace Tests\Feature\Weddings;

use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WeddingContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_couple_sets_who_guests_ask_and_guests_see_it_in_the_footer(): void
    {
        $wedding = Wedding::factory()->create();
        $household = Household::factory()->for($wedding)->create();

        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page->where('invitation.wedding.contact', null));

        $this->actingAs($wedding->owner)
            ->put(route('weddings.content.contact', $wedding), [
                'contact_name' => 'Lea, Trauzeugin',
                'contact_email' => 'lea@example.ch',
                'contact_phone' => '+41 79 123 45 67',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page
                ->where('invitation.wedding.contact.name', 'Lea, Trauzeugin')
                ->where('invitation.wedding.contact.email', 'lea@example.ch')
                ->where('invitation.wedding.contact.phone', '+41 79 123 45 67')
                ->where('invitation.wedding.privacyUrl', rtrim((string) config('app.url'), '/').'/datenschutz')
            );
    }

    public function test_a_contact_needs_a_name_and_a_real_address(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->put(route('weddings.content.contact', $wedding), ['contact_email' => 'keine-adresse'])
            ->assertSessionHasErrors(['contact_name', 'contact_email']);
    }

    public function test_couples_cannot_change_another_weddings_footer(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs(Wedding::factory()->create()->owner)
            ->put(route('weddings.content.contact', $wedding), ['contact_name' => 'X'])
            ->assertForbidden();
    }
}
