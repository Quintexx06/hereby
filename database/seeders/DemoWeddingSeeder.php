<?php

namespace Database\Seeders;

use App\Enums\ContentBlockType;
use App\Enums\EventType;
use App\Enums\Locale;
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
            'partner_one' => 'Anna',
            'partner_two' => 'Luca',
            'wedding_date' => '2027-06-19',
            'rsvp_deadline' => '2027-05-01',
            'default_locale' => Locale::GermanSwiss,
        ]);

        $civil = $this->event($wedding, EventType::CivilCeremony, '2027-06-18 14:00', 'Stadthaus Luzern', 'Hirschengraben 17, 6003 Luzern');
        $apero = $this->event($wedding, EventType::Reception, '2027-06-19 15:30', 'Seeterrasse', 'Seestrasse 1, 6354 Vitznau');
        $dinner = $this->event($wedding, EventType::Dinner, '2027-06-19 18:30', 'Grand Salon', 'Seestrasse 1, 6354 Vitznau');
        $party = $this->event($wedding, EventType::Party, '2027-06-19 22:00', 'Bootshaus', 'Seestrasse 1, 6354 Vitznau');

        $this->household($wedding, 'Familie Meier', Locale::GermanSwiss, ['Heidi', 'Peter', 'Lina'], [$civil, $apero, $dinner, $party]);
        $this->household($wedding, 'Famille Rossi', Locale::French, ['Camille', 'Marco'], [$apero, $dinner, $party]);
        $this->household($wedding, 'Famiglia Bernasconi', Locale::Italian, ['Giulia'], [$apero, $dinner, $party]);
        $this->household($wedding, 'The Carters', Locale::English, ['Emma', 'James'], [$party]);

        $this->blocks($wedding);

        $wedding->households()->each(function (Household $household): void {
            $this->command->info("{$household->name}: ".route('invitation.show', $household));
        });
    }

    /**
     * The couple's own pages: their story in chapters, and the practical ones.
     */
    private function blocks(Wedding $wedding): void
    {
        $blocks = [
            ContentBlockType::Story->value => [
                'body' => 'Zwei Menschen, eine Stadt, und ein Zug, der zu spät kam. Hier ist, wie aus einem Zufall ein Ja wurde.',
                'items' => [
                    ['question' => '2017 · Luzern', 'answer' => 'Der Zug nach Zürich fiel aus. Anna und Luca teilten sich das letzte Taxi und stritten die ganze Fahrt über die beste Pizza der Stadt.'],
                    ['question' => '2019 · Die erste Wohnung', 'answer' => 'Drei Zimmer, ein Balkon mit Blick auf die Reuss und ein Sofa, das nicht durch die Tür passte.'],
                    ['question' => '2023 · Lissabon', 'answer' => 'Eine Reise, zu viele Pastéis de Nata und der Moment, in dem beide wussten: das ist es.'],
                    ['question' => '2026 · Der Antrag', 'answer' => 'Auf dem Steg in Vitznau, bei Sonnenaufgang. Sie hat Ja gesagt, bevor er fertig war.'],
                ],
            ],
            ContentBlockType::DressCode->value => [
                'body' => 'Festlich und sommerlich. Der Apéro ist auf dem Rasen, also lieber Blockabsätze als Stilettos.',
            ],
            ContentBlockType::Travel->value => [
                'body' => 'Mit dem Zug bis Luzern, dann mit dem Schiff nach Vitznau (40 Minuten). Parkplätze gibt es beim Bootshaus.',
            ],
            ContentBlockType::Faq->value => [
                'items' => [
                    ['question' => 'Sind Kinder willkommen?', 'answer' => 'Sehr! Für die Kleinen gibt es ein eigenes Menü und eine Spielecke.'],
                    ['question' => 'Was schenken wir euch?', 'answer' => 'Eure Anwesenheit. Wer mag, darf etwas an unsere Hochzeitsreise beitragen.'],
                ],
            ],
        ];

        foreach (array_keys($blocks) as $position => $type) {
            $wedding->contentBlocks()->create([
                'type' => $type,
                'position' => $position,
                'content' => [$wedding->default_locale->value => ['title' => null, 'body' => null, 'items' => [], ...$blocks[$type]]],
            ]);
        }
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
