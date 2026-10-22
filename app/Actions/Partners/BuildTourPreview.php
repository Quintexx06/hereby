<?php

namespace App\Actions\Partners;

use App\Enums\ContentBlockType;
use App\Enums\EventType;
use App\Enums\Locale;
use App\Enums\WeddingTemplate;
use App\Models\ContentBlock;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Partner;
use App\Models\Wedding;
use App\Support\TourPreviewPhoto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * «Vorschau für die Besichtigung» (3.3): two names and a date give a
 * finished-looking site at a Showcase venue, from its profile. Built in
 * memory: nothing is stored, no link can be shared.
 */
class BuildTourPreview
{
    /**
     * @param  array{first: string, second: string, date: string, template: string}  $input
     */
    public function handle(Partner $venue, array $input): Household
    {
        $date = Carbon::parse($input['date'], 'Europe/Zurich');
        $wedding = new Wedding([
            'partner_one' => $input['first'],
            'partner_two' => $input['second'],
            'couple_names' => "{$input['first']} & {$input['second']}",
            'wedding_date' => $date->toDateString(),
            'rsvp_deadline' => $date->copy()->subWeeks(6)->toDateString(),
            'default_locale' => Locale::GermanSwiss,
            'languages' => [Locale::GermanSwiss],
            'template' => WeddingTemplate::from($input['template']),
            'venue_name' => $venue->name,
            'venue_address' => $venue->address,
            'venue_postcode' => $venue->postcode,
            'venue_town' => $venue->town,
        ]);

        $events = new Collection(collect([[EventType::Ceremony, '15:00'], [EventType::Reception, '16:30'], [EventType::Dinner, '18:30'], [EventType::Party, '22:00']])
            ->map(fn (array $part, int $index): Event => (new Event([
                'type' => $part[0],
                'starts_at' => Carbon::parse("{$date->toDateString()} {$part[1]}", 'Europe/Zurich')->utc(),
                'location_name' => $venue->name,
                'address' => trim("{$venue->address}, {$venue->postcode} {$venue->town}", ', '),
            ]))->forceFill(['id' => $index + 1]))
            ->all());

        $wedding->setRelation('events', $events);
        $wedding->setRelation('couplePhotos', new Collection($venue->photos->map(TourPreviewPhoto::from(...))->all()));
        $wedding->setRelation('partners', new Collection([$venue]));
        $wedding->setRelation('contentBlocks', $this->blocks($venue));

        $household = new Household(['name' => $wedding->couple_names, 'locale' => Locale::GermanSwiss, 'plus_one_allowed' => false]);
        $household->setRelation('wedding', $wedding);
        $household->setRelation('events', $events);
        $household->setRelation('guests', new Collection([(new Guest(['first_name' => $input['first']]))->setRelation('responses', new Collection)]));

        return $household;
    }

    /**
     * The venue's own texts as the site's «Location», «Anreise» and «Übernachten».
     *
     * @return Collection<int, ContentBlock>
     */
    private function blocks(Partner $venue): Collection
    {
        $texts = [ContentBlockType::Venue->value => $venue->about, ContentBlockType::Travel->value => $venue->travel, ContentBlockType::Stay->value => $venue->stay];

        // The venue block always shows, with its address and route.
        return new Collection(collect(array_filter($texts, fn (?string $text, string $type): bool => $text !== null || $type === ContentBlockType::Venue->value, ARRAY_FILTER_USE_BOTH))
            ->map(fn (?string $text, string $type): ContentBlock => (new ContentBlock([
                'type' => $type,
                'content' => [Locale::GermanSwiss->value => ['title' => null, 'body' => $text, 'items' => []]],
            ]))->forceFill(['id' => crc32($type)]))
            ->values()
            ->all());
    }
}
