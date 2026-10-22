<?php

namespace Database\Seeders;

use App\Enums\EventType;
use App\Enums\Locale;
use App\Enums\Plan;
use App\Enums\WeddingTemplate;
use App\Models\Event;
use App\Models\Household;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingPhoto;
use App\Support\WebImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Roadmap 0.2: one demo site for one real venue (spec 2026-10-13). A
 * fictional couple at the real Seehotel Kastanienbaum on Lake Lucerne,
 * at full quality, to show the venue what its guests would see.
 * Run: php artisan db:seed --class=KastanienbaumDemoSeeder
 */
class KastanienbaumDemoSeeder extends Seeder
{
    private const string SLUG = 'lea-und-jonas';

    public function run(): void
    {
        Wedding::query()->where('slug', self::SLUG)->get()->each->delete();

        $owner = User::query()->firstOrCreate(
            ['email' => 'demo-kastanienbaum@example.com'],
            ['name' => 'Lea & Jonas', 'password' => 'password', 'email_verified_at' => now()],
        );

        $wedding = Wedding::factory()->for($owner, 'owner')->create([
            'slug' => self::SLUG,
            'couple_names' => 'Lea & Jonas',
            'partner_one' => 'Lea',
            'partner_two' => 'Jonas',
            'wedding_date' => '2027-06-12',
            'rsvp_deadline' => '2027-04-30',
            'default_locale' => Locale::GermanSwiss,
            'languages' => [Locale::GermanSwiss, Locale::French, Locale::English],
            'template' => WeddingTemplate::Arve,
            'plan' => Plan::Atelier,
            'venue_name' => 'Seehotel Kastanienbaum',
            'venue_address' => 'St. Niklausenstrasse 105',
            'venue_postcode' => '6047',
            'venue_town' => 'Kastanienbaum',
        ]);

        $place = 'St. Niklausenstrasse 105, 6047 Kastanienbaum';
        $ceremony = $this->event($wedding, EventType::Ceremony, '14:30', 'Seehotel Kastanienbaum', $place);
        $apero = $this->event($wedding, EventType::Reception, '15:30', 'Terrasse · Seehotel Kastanienbaum', $place);
        $dinner = $this->event($wedding, EventType::Dinner, '18:30', 'Pavillon · Seehotel Kastanienbaum', $place);
        $party = $this->event($wedding, EventType::Party, '22:00', 'Seehotel Kastanienbaum', $place);
        $all = [$ceremony, $apero, $dinner, $party];

        $this->household($wedding, 'Familie Brunner', Locale::GermanSwiss, ['Ruth' => 'Pilatus', 'Hans' => 'Pilatus'], $all);
        $this->household($wedding, 'Famille Dubois', Locale::French, ['Claire' => 'Rigi', 'Antoine' => 'Rigi'], $all);
        $this->household($wedding, 'Sarah & Tom Miller', Locale::English, ['Sarah' => 'Rigi', 'Tom' => 'Rigi'], $all);
        $this->household($wedding, 'Nina Keller', Locale::GermanSwiss, ['Nina' => 'Bürgenstock'], $all);
        $this->household($wedding, 'Team Jonas', Locale::GermanSwiss, ['Marco' => null, 'Luca' => null], [$party]);
        $wedding->forceFill(['seating_published_at' => now()])->save();

        $this->blocks($wedding);
        $this->photos($wedding);

        $wedding->households()->each(function (Household $household): void {
            $this->command->info("{$household->name}: ".route('invitation.show', $household));
        });
    }

    private function event(Wedding $wedding, EventType $type, string $time, string $location, string $address): Event
    {
        return Event::factory()->for($wedding)->ofType($type)->create([
            'starts_at' => now()->parse("2027-06-12 {$time}", 'Europe/Zurich')->utc(),
            'location_name' => $location,
            'address' => $address,
        ]);
    }

    /**
     * @param  array<string, string|null>  $guests  First name => table.
     * @param  list<Event>  $events
     */
    private function household(Wedding $wedding, string $name, Locale $locale, array $guests, array $events): void
    {
        $household = Household::factory()->for($wedding)->speaking($locale)->create(['name' => $name]);

        foreach ($guests as $firstName => $table) {
            $household->guests()->create(['first_name' => $firstName, 'table_name' => $table]);
        }

        $household->events()->attach(collect($events)->pluck('id'));
    }

    /**
     * Story, venue, dress code, travel, rooms and FAQ, in the guests' languages.
     */
    private function blocks(Wedding $wedding): void
    {
        $content = $this->content();
        $order = ['story', 'venue', 'dress_code', 'travel', 'stay', 'faq'];

        foreach ($order as $position => $type) {
            $wedding->contentBlocks()->create([
                'type' => $type,
                'position' => $position,
                'content' => collect($content[$type] ?? [])
                    ->map(fn (array $text): array => ['title' => null, 'body' => null, 'items' => [], ...$text])
                    ->all(),
            ]);
        }
    }

    /**
     * Block texts per type and language (data/kastanienbaum.php).
     *
     * @return array<string, array<string, array<string, mixed>>>
     */
    private function content(): array
    {
        return require __DIR__.'/data/kastanienbaum.php';
    }

    /**
     * Three photos from the licensed landing set (public/images/landing/CREDITS.md).
     */
    private function photos(Wedding $wedding): void
    {
        foreach (['hero-veil-1440.webp', 'lake-jetty-1200.webp', 'table-candles.webp'] as $position => $file) {
            $copy = WebImage::jpeg((string) file_get_contents(public_path("images/landing/{$file}")), 1600);

            if ($copy === null) {
                continue;
            }

            $path = WeddingPhoto::directoryFor($wedding->id).'/'.Str::uuid().'.jpg';
            Storage::disk(WeddingPhoto::DISK)->put($path, $copy['bytes']);

            $photo = new WeddingPhoto(['position' => $position]);
            $photo->wedding_id = $wedding->id;
            $photo->path = $path;
            $photo->width = $copy['width'];
            $photo->height = $copy['height'];
            $photo->save();
        }
    }
}
