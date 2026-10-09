<?php

namespace Tests\Feature\Guests;

use App\Enums\EventType;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Household;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GuestImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_preview_flags_guests_already_on_the_list_and_saves_nothing(): void
    {
        $wedding = Wedding::factory()->create();
        Guest::factory()->for(Household::factory()->for($wedding))->create(['first_name' => 'Heidi', 'last_name' => 'Meier']);

        $this->actingAs($wedding->owner)
            ->postJson(route('weddings.guests.preview', $wedding), ['text' => "Heidi und Peter Meier\nGiulia Bernasconi"])
            ->assertOk()
            ->assertJsonPath('households.0.guests.0.duplicate', true)
            ->assertJsonPath('households.0.guests.1.duplicate', false)
            ->assertJsonPath('households.1.name', 'Giulia Bernasconi');

        $this->assertSame(1, Household::count());
    }

    public function test_unreadable_files_get_a_clear_message(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->postJson(route('weddings.guests.preview', $wedding), ['file' => UploadedFile::fake()->createWithContent('liste.xlsx', 'not a zip')])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Diese Datei können wir nicht lesen. Versucht es als CSV oder fügt die Zellen ein.');
    }

    public function test_confirmed_households_are_saved_and_invited_to_every_event(): void
    {
        $wedding = Wedding::factory()->create();
        Event::factory()->for($wedding)->ofType(EventType::Dinner)->create();
        Event::factory()->for($wedding)->ofType(EventType::Party)->create();

        $this->actingAs($wedding->owner)
            ->post(route('weddings.households.store', $wedding), ['households' => [
                ['name' => 'Familie Meier', 'email' => 'heidi@meier.ch', 'guests' => [
                    ['first_name' => 'Heidi', 'last_name' => 'Meier'],
                    ['first_name' => 'Lina', 'last_name' => 'Meier', 'is_child' => true],
                ]],
            ]])
            ->assertRedirect(route('weddings.guests.index', $wedding));

        $household = $wedding->households()->first();
        $this->assertSame('heidi@meier.ch', $household->email);
        $this->assertSame(2, $household->guests()->count());
        $this->assertTrue($household->guests()->where('first_name', 'Lina')->first()->is_child);
        $this->assertSame(2, $household->events()->count());
    }

    public function test_couples_cannot_import_into_another_wedding(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs(User::factory()->create())
            ->postJson(route('weddings.guests.preview', $wedding), ['text' => 'Heidi'])
            ->assertForbidden();
    }
}
