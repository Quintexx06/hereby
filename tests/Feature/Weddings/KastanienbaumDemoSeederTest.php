<?php

namespace Tests\Feature\Weddings;

use App\Enums\Plan;
use App\Models\Wedding;
use App\Models\WeddingPhoto;
use Database\Seeders\KastanienbaumDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class KastanienbaumDemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_venue_demo_is_a_live_wedding_at_the_real_address(): void
    {
        $this->seed(KastanienbaumDemoSeeder::class);

        $wedding = Wedding::query()->where('slug', 'lea-und-jonas')->sole();
        $this->assertSame(Plan::Atelier, $wedding->plan);
        $this->assertSame('Seehotel Kastanienbaum', $wedding->venue_name);
        $this->assertSame('St. Niklausenstrasse 105', $wedding->venue_address);
        $this->assertCount(4, $wedding->events);
        $this->assertCount(3, $wedding->couplePhotos);
        Storage::disk(WeddingPhoto::DISK)->assertExists($wedding->couplePhotos->first()->path);
        $this->assertSame([], $wedding->way_home ?? [], 'Last connections come from the real timetable, never the seeder.');

        $household = $wedding->households()->where('name', 'Famille Dubois')->sole();
        $this->get(route('invitation.show', $household))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('invitation.wedding.coupleNames', 'Lea & Jonas')
                ->has('invitation.wedding.photos', 3)
                ->where('locale', 'fr'));
    }

    public function test_seeding_twice_does_not_duplicate_the_demo(): void
    {
        $this->seed(KastanienbaumDemoSeeder::class);
        $this->seed(KastanienbaumDemoSeeder::class);

        $this->assertSame(1, Wedding::query()->where('slug', 'lea-und-jonas')->count());
    }
}
