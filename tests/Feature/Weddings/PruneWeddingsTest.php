<?php

namespace Tests\Feature\Weddings;

use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruneWeddingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_data_is_deleted_after_the_retention_window(): void
    {
        $expired = Wedding::factory()->heldMonthsAgo(13)->create();
        $recent = Wedding::factory()->heldMonthsAgo(11)->create();
        $guest = Guest::factory()->for(Household::factory()->for($expired))->create();

        $this->artisan('model:prune', ['--model' => Wedding::class])->assertSuccessful();

        $this->assertModelMissing($expired);
        $this->assertModelMissing($guest);
        $this->assertModelExists($recent);
    }

    public function test_dietary_notes_are_encrypted_at_rest(): void
    {
        $guest = Guest::factory()->create(['dietary_notes' => 'Nut allergy']);

        $stored = $guest->getConnection()->table('guests')->where('id', $guest->id)->value('dietary_notes');

        $this->assertNotSame('Nut allergy', $stored);
        $this->assertSame('Nut allergy', $guest->fresh()->dietary_notes);
    }
}
