<?php

namespace Tests\Feature\Invitations;

use App\Enums\ContentBlockType;
use App\Enums\EventType;
use App\Enums\Locale;
use App\Models\ContentBlock;
use App\Models\Event;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class InvitationBlocksTest extends TestCase
{
    use RefreshDatabase;

    public function test_household_sees_blocks_for_everyone_and_for_its_own_events_in_order(): void
    {
        $wedding = Wedding::factory()->create();
        $dinner = Event::factory()->for($wedding)->ofType(EventType::Dinner)->create();
        $civil = Event::factory()->for($wedding)->ofType(EventType::CivilCeremony)->create();
        $household = Household::factory()->for($wedding)->create();
        $household->events()->attach($dinner);

        ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::Faq, 'position' => 2, 'content' => ['de_CH' => ['items' => [['question' => 'Kinder?', 'answer' => 'Gerne.']]]]]);
        ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::Story, 'position' => 1, 'content' => ['de_CH' => ['body' => 'Wir trafen uns am See.']]]);
        ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::DressCode, 'event_id' => $dinner->id, 'position' => 3, 'content' => ['de_CH' => ['body' => 'Festlich.']]]);
        ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::Venue, 'event_id' => $civil->id, 'position' => 0, 'content' => ['de_CH' => ['body' => 'Standesamt.']]]);

        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page
                ->has('invitation.blocks', 3)
                ->where('invitation.blocks.0.type', 'story')
                ->where('invitation.blocks.0.body', 'Wir trafen uns am See.')
                ->where('invitation.blocks.1.type', 'faq')
                ->where('invitation.blocks.1.items.0.question', 'Kinder?')
                ->where('invitation.blocks.2.type', 'dress_code')
            );
    }

    public function test_text_falls_back_to_the_main_language_and_empty_blocks_are_hidden(): void
    {
        $wedding = Wedding::factory()->create(['default_locale' => Locale::GermanSwiss]);
        $household = Household::factory()->for($wedding)->speaking(Locale::French)->create();
        ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::Story, 'position' => 0, 'content' => [
            'de_CH' => ['title' => 'Wie alles begann', 'body' => 'Am See.'],
            'fr' => ['title' => '', 'body' => 'Au bord du lac.'],
        ]]);
        ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::DressCode, 'position' => 1, 'content' => ['de_CH' => ['body' => 'Festlich.']]]);
        ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::Faq, 'position' => 2, 'content' => ['de_CH' => ['items' => []]]]);

        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page
                ->has('invitation.blocks', 2)
                ->where('invitation.blocks.0.body', 'Au bord du lac.')
                ->where('invitation.blocks.0.title', null)
                ->where('invitation.blocks.1.body', 'Festlich.')
            );
    }

    public function test_venue_block_carries_the_venue_and_a_route_link(): void
    {
        $wedding = Wedding::factory()->create([
            'venue_name' => 'Hotel Vitznauerhof',
            'venue_address' => 'Seestrasse 80',
            'venue_postcode' => '6354',
            'venue_town' => 'Vitznau',
        ]);
        $household = Household::factory()->for($wedding)->create();
        ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::Venue, 'position' => 0, 'content' => ['de_CH' => ['body' => 'Parkplätze beim Bootshaus.']]]);

        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page
                ->where('invitation.blocks.0.venue.name', 'Hotel Vitznauerhof')
                ->where('invitation.blocks.0.venue.address', 'Seestrasse 80, 6354 Vitznau')
                ->where('invitation.blocks.0.venue.route', fn (string $url) => str_contains($url, urlencode('Seestrasse 80, 6354 Vitznau')))
            );
    }
}
