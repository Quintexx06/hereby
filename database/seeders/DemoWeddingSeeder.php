<?php

namespace Database\Seeders;

use App\Enums\EventType;
use App\Enums\Locale;
use App\Enums\WeddingTheme;
use App\Models\Event;
use App\Models\Household;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Database\Seeder;

/**
 * A realistic demo wedding for local development and Phase 0 pitches:
 * multi-language households with different event access.
 */
class DemoWeddingSeeder extends Seeder
{
    public function run(): void
    {
        $wedding = Wedding::factory()->for(User::query()->firstOrFail(), 'owner')->create([
            'slug' => 'anna-und-luca',
            'couple_names' => 'Anna & Luca',
            'wedding_date' => '2027-06-19',
            'rsvp_deadline' => '2027-05-01',
            'default_locale' => Locale::GermanSwiss,
            'theme' => WeddingTheme::Ivory,
        ]);

        $civil = $this->event($wedding, EventType::CivilCeremony, '2027-06-18 14:00', 'Stadthaus Luzern', 'Hirschengraben 17, 6003 Luzern');
        $apero = $this->event($wedding, EventType::Reception, '2027-06-19 15:30', 'Seeterrasse', 'Seestrasse 1, 6354 Vitznau');
        $dinner = $this->event($wedding, EventType::Dinner, '2027-06-19 18:30', 'Grand Salon', 'Seestrasse 1, 6354 Vitznau');
        $party = $this->event($wedding, EventType::Party, '2027-06-19 22:00', 'Bootshaus', 'Seestrasse 1, 6354 Vitznau');

        $this->household($wedding, 'Familie Meier', Locale::GermanSwiss, ['Heidi', 'Peter', 'Lina'], [$civil, $apero, $dinner, $party]);
        $this->household($wedding, 'Famille Rossi', Locale::French, ['Camille', 'Marco'], [$apero, $dinner, $party]);
        $this->household($wedding, 'Famiglia Bernasconi', Locale::Italian, ['Giulia'], [$apero, $dinner, $party]);
        $this->household($wedding, 'The Carters', Locale::English, ['Emma', 'James'], [$party]);

        $wedding->households()->each(function (Household $household): void {
            $this->command->info("{$household->name}: ".route('invitation.show', $household));
        });
    }

    private function event(Wedding $wedding, EventType $type, string $startsAt, string $location, string $address): Event
    {
        return Event::factory()->for($wedding)->ofType($type)->create([
            'starts_at' => now()->parse($startsAt, 'Europe/Zurich')->utc(),
            'location_name' => $location,
            'address' => $address,
        ]);
    }

    /**
     * @param  list<string>  $guestNames
     * @param  list<Event>  $events
     */
    private function household(Wedding $wedding, string $name, Locale $locale, array $guestNames, array $events): void
    {
        $household = Household::factory()->for($wedding)->speaking($locale)->create(['name' => $name]);

        foreach ($guestNames as $firstName) {
            $household->guests()->create(['first_name' => $firstName]);
        }

        $household->events()->attach(collect($events)->pluck('id'));
    }
}
