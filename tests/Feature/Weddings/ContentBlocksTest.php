<?php

namespace Tests\Feature\Weddings;

use App\Enums\ContentBlockType;
use App\Enums\Locale;
use App\Models\ContentBlock;
use App\Models\Event;
use App\Models\Household;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ContentBlocksTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_lists_blocks_languages_and_events(): void
    {
        $wedding = Wedding::factory()->create(['languages' => [Locale::GermanSwiss, Locale::French]]);
        Event::factory()->for($wedding)->create();
        ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::Venue]);

        $this->actingAs($wedding->owner)
            ->get(route('weddings.content.index', $wedding))
            ->assertInertia(fn (Assert $page) => $page
                ->component('content/Index')
                ->where('wedding.languages', ['de_CH', 'fr'])
                ->where('blocks.0.type', 'venue')
                ->has('events', 1)
            );
    }

    public function test_couple_adds_one_block_per_type(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->post(route('weddings.content.store', $wedding), ['type' => 'story'])
            ->assertRedirect();
        $this->actingAs($wedding->owner)
            ->post(route('weddings.content.store', $wedding), ['type' => 'story'])
            ->assertSessionHasErrors('type');

        $this->assertSame(1, $wedding->contentBlocks()->count());
    }

    public function test_couple_edits_text_per_language_and_visibility(): void
    {
        $wedding = Wedding::factory()->create(['languages' => [Locale::GermanSwiss, Locale::French]]);
        $dinner = Event::factory()->for($wedding)->create();
        $block = ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::Faq]);

        $this->actingAs($wedding->owner)
            ->put(route('weddings.content.update', [$wedding, $block]), [
                'event_id' => $dinner->id,
                'content' => [
                    'de_CH' => ['title' => '', 'items' => [['question' => 'Parkplätze?', 'answer' => 'Beim See.'], ['question' => '', 'answer' => '']]],
                    'fr' => ['items' => [['question' => 'Parking?', 'answer' => 'Au bord du lac.']]],
                    'it' => ['body' => 'nicht verwendet'],
                ],
            ])
            ->assertSessionHasNoErrors();

        $block->refresh();
        $this->assertSame($dinner->id, $block->event_id);
        $this->assertSame([['question' => 'Parkplätze?', 'answer' => 'Beim See.']], $block->content['de_CH']['items']);
        $this->assertArrayNotHasKey('it', $block->content);
    }

    public function test_visibility_must_be_one_of_the_weddings_events(): void
    {
        $wedding = Wedding::factory()->create();
        $foreign = Event::factory()->create();
        $block = ContentBlock::factory()->for($wedding)->create();

        $this->actingAs($wedding->owner)
            ->put(route('weddings.content.update', [$wedding, $block]), ['event_id' => $foreign->id, 'content' => []])
            ->assertSessionHasErrors('event_id');
    }

    public function test_couple_reorders_and_removes_blocks(): void
    {
        $wedding = Wedding::factory()->create();
        $story = ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::Story, 'position' => 0]);
        $faq = ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::Faq, 'position' => 1]);

        $this->actingAs($wedding->owner)
            ->put(route('weddings.content.reorder', $wedding), ['ids' => [$faq->id, $story->id]])
            ->assertRedirect();
        $this->assertSame([$faq->id, $story->id], $wedding->contentBlocks()->pluck('id')->all());

        $this->actingAs($wedding->owner)->delete(route('weddings.content.destroy', [$wedding, $story]))->assertRedirect();
        $this->assertModelMissing($story);
    }

    public function test_blocks_of_another_wedding_cannot_be_touched(): void
    {
        $wedding = Wedding::factory()->create();
        $other = ContentBlock::factory()->create();

        $this->actingAs($wedding->owner)
            ->delete(route('weddings.content.destroy', [$wedding, $other]))
            ->assertNotFound();
        $this->actingAs(User::factory()->create())
            ->get(route('weddings.content.index', $wedding))
            ->assertForbidden();
    }

    public function test_preview_shows_the_invitation_in_the_chosen_language_without_opening_anything(): void
    {
        $wedding = Wedding::factory()->create(['languages' => [Locale::GermanSwiss, Locale::French]]);
        $household = Household::factory()->for($wedding)->create();
        Event::factory()->for($wedding)->create();

        $this->actingAs($wedding->owner)
            ->get(route('weddings.preview', ['wedding' => $wedding, 'sprache' => 'fr']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('invitation/Show')
                ->where('locale', 'fr')
                ->where('preview', true)
                ->has('invitation.events', 1)
            );

        $this->assertNull($household->fresh()->opened_at);
        $this->actingAs(User::factory()->create())->get(route('weddings.preview', $wedding))->assertForbidden();
    }

    public function test_hotel_links_must_be_web_addresses(): void
    {
        $wedding = Wedding::factory()->create();
        $block = ContentBlock::factory()->for($wedding)->create(['type' => ContentBlockType::Stay]);

        $this->actingAs($wedding->owner)
            ->put(route('weddings.content.update', [$wedding, $block]), ['content' => ['de_CH' => [
                'items' => [['question' => 'Hotel', 'answer' => '', 'url' => 'javascript:alert(1)']],
            ]]])
            ->assertSessionHasErrors('content.de_CH.items.0.url');
    }
}
