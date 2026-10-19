<?php

namespace Tests\Feature\Partners;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProspectImportTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }

    public function test_the_team_pastes_a_list_of_prospects_once_and_again_without_doubles(): void
    {
        Mail::fake();
        $lines = "Hotel Belvedere; Spiez; Info@Belvedere.ch; https://belvedere.ch; Ihr Garten am Thunersee …\n\nSchloss Wart\tNeftenbach\n; Ohne Namen\nGasthof Kreuz; Bern; keine-adresse";

        $this->actingAs($this->admin())
            ->post(route('admin.partners.import'), ['lines' => $lines])
            ->assertSessionHasNoErrors()
            ->assertInertiaFlash('toast.message', '2 Interessenten angelegt, Zeile 4, 5 nicht lesbar.');

        $belvedere = Partner::query()->where('name', 'Hotel Belvedere')->sole();
        $this->assertSame('info@belvedere.ch', $belvedere->email);
        $this->assertSame('Ihr Garten am Thunersee …', $belvedere->pitch_note);
        $this->assertSame('Neftenbach', Partner::query()->where('name', 'Schloss Wart')->value('town'));
        $this->assertFalse($belvedere->users()->exists());
        Mail::assertNothingQueued();

        $this->post(route('admin.partners.import'), ['lines' => 'hotel belvedere; Spiez'])
            ->assertInertiaFlash('toast.message', '0 Interessenten angelegt, 1 schon in der Liste.');
        $this->assertSame(2, Partner::query()->count());

        $this->get(route('admin.partners.index'))
            ->assertInertia(fn (Assert $page) => $page->where('partners.0.pitch_note', 'Ihr Garten am Thunersee …'));
    }

    public function test_only_the_team_imports_and_the_note_stays_out_of_serialised_partners(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.partners.import'), ['lines' => 'Hotel Belvedere'])
            ->assertForbidden();

        $partner = Partner::factory()->create(['pitch_note' => 'Nur für das Team']);
        $this->assertArrayNotHasKey('pitch_note', $partner->toArray());
    }

    public function test_inviting_a_prospect_without_an_address_says_so_instead_of_failing(): void
    {
        $prospect = Partner::factory()->create(['email' => null]);

        $this->actingAs($this->admin())
            ->post(route('admin.partners.resend', $prospect))
            ->assertRedirect()
            ->assertInertiaFlash('toast.type', 'error');

        $this->assertFalse($prospect->users()->exists());
    }
}
