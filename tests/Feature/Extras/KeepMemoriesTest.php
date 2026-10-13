<?php

namespace Tests\Feature\Extras;

use App\Enums\Plan;
use App\Models\Event;
use App\Models\Guest;
use App\Models\GuestPhoto;
use App\Models\Household;
use App\Models\NewspaperArticle;
use App\Models\ProgrammeItem;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KeepMemoriesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake(GuestPhoto::DISK);
    }

    public function test_kept_memories_survive_the_retention_window_but_guest_data_does_not(): void
    {
        $wedding = Wedding::factory()->heldMonthsAgo(13)->create([
            'plan' => Plan::Atelier,
            'memories_kept_until' => now()->addMonths(6)->toDateString(),
        ]);
        $household = Household::factory()->for($wedding)->create();
        $guest = Guest::factory()->for($household)->create(['dietary_notes' => 'Nüsse']);
        $item = ProgrammeItem::factory()->for($wedding)->for($household)->create();
        $photo = GuestPhoto::factory()->for($wedding)->create();
        $article = NewspaperArticle::factory()->for($wedding)->create();

        $this->artisan('hereby:strip-guest-data')->assertSuccessful();
        $this->artisan('model:prune', ['--model' => Wedding::class])->assertSuccessful();

        $this->assertModelExists($wedding);
        $this->assertModelExists($photo);
        $this->assertModelExists($article);
        $this->assertModelMissing($household);
        $this->assertModelMissing($guest);
        $this->assertModelMissing($item);
        $this->assertNotNull($wedding->refresh()->guest_data_deleted_at);
    }

    public function test_once_the_kept_year_is_over_everything_goes(): void
    {
        $wedding = Wedding::factory()->heldMonthsAgo(26)->create([
            'plan' => Plan::Atelier,
            'memories_kept_until' => now()->subDay()->toDateString(),
        ]);
        $photo = GuestPhoto::factory()->for($wedding)->create();

        $this->artisan('model:prune', ['--model' => Wedding::class])->assertSuccessful();

        $this->assertModelMissing($wedding);
        $this->assertModelMissing($photo);
    }

    public function test_weddings_inside_the_window_keep_their_guests(): void
    {
        $wedding = Wedding::factory()->heldMonthsAgo(11)->create([
            'plan' => Plan::Atelier,
            'memories_kept_until' => now()->addYear()->toDateString(),
        ]);
        $household = Household::factory()->for($wedding)->create();
        $wedding->events()->save(Event::factory()->make());

        $this->artisan('hereby:strip-guest-data')->assertSuccessful();

        $this->assertModelExists($household);
        $this->assertNull($wedding->refresh()->guest_data_deleted_at);
    }
}
