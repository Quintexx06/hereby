<?php

namespace Tests\Feature\Invitations;

use App\Enums\EventType;
use App\Enums\Locale;
use App\Enums\ResponseStatus;
use App\Models\Event;
use App\Models\EventResponse;
use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    private Wedding $wedding;

    private Household $household;

    private Event $ceremony;

    private Event $dinner;

    private Guest $heidi;

    private Guest $lina;

    protected function setUp(): void
    {
        parent::setUp();

        $this->wedding = Wedding::factory()->create([
            'menu_options' => [['key' => 'fleisch', 'label' => 'Kalbsfilet'], ['key' => 'vegi', 'label' => 'Risotto']],
            'children_menu' => true,
        ]);
        $this->ceremony = Event::factory()->for($this->wedding)->ofType(EventType::Ceremony)->create();
        $this->dinner = Event::factory()->for($this->wedding)->ofType(EventType::Dinner)->create();
        $this->household = Household::factory()->for($this->wedding)->create();
        $this->household->events()->attach([$this->ceremony->id, $this->dinner->id]);
        $this->heidi = Guest::factory()->for($this->household)->create(['first_name' => 'Heidi']);
        $this->lina = Guest::factory()->for($this->household)->child()->create(['first_name' => 'Lina']);
    }

    /**
     * Everyone attends everything; Heidi eats meat, Lina the children's menu.
     *
     * @return array<string, mixed>
     */
    private function allAttending(array $overrides = []): array
    {
        return array_merge([
            'answers' => [
                ['guest_id' => $this->heidi->id, 'event_id' => $this->ceremony->id, 'status' => 'attending'],
                ['guest_id' => $this->heidi->id, 'event_id' => $this->dinner->id, 'status' => 'attending', 'menu' => 'fleisch'],
                ['guest_id' => $this->lina->id, 'event_id' => $this->ceremony->id, 'status' => 'attending'],
                ['guest_id' => $this->lina->id, 'event_id' => $this->dinner->id, 'status' => 'attending', 'menu' => 'children'],
            ],
        ], $overrides);
    }

    private function reply(array $payload): TestResponse
    {
        return $this->put(route('invitation.reply.update', $this->household), $payload);
    }

    public function test_reply_page_shows_only_this_household_in_its_language(): void
    {
        $this->household->update(['locale' => Locale::French]);
        $other = Household::factory()->for($this->wedding)->create();
        Guest::factory()->for($other)->create(['first_name' => 'Fremd']);
        $this->heidi->update(['dietary_notes' => 'Nüsse']);

        $this->get(route('invitation.reply', $this->household))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('invitation/Reply')
                ->where('locale', 'fr')
                ->has('reply.guests', 2)
                ->has('reply.events', 2)
                ->where('reply.guests.0.hasDietaryNotes', true)
                ->missing('reply.guests.0.dietaryNotes')
                ->where('reply.questions.menus.0.key', 'fleisch')
                ->where('reply.open', true)
            );

        $this->assertStringNotContainsString('Nüsse', $this->get(route('invitation.reply', $this->household))->getContent());
        $this->assertStringNotContainsString('Fremd', $this->get(route('invitation.reply', $this->household))->getContent());
    }

    public function test_saving_stores_one_response_per_guest_and_event(): void
    {
        $this->reply($this->allAttending())
            ->assertRedirect(route('invitation.show', $this->household));

        $this->assertSame(4, EventResponse::query()->where('status', ResponseStatus::Attending)->count());
        $this->assertSame('children', $this->lina->responses()->where('event_id', $this->dinner->id)->value('menu_choice'));
        $this->assertNotNull($this->household->fresh()->responded_at);
    }

    public function test_saving_again_updates_instead_of_duplicating(): void
    {
        $this->reply($this->allAttending());
        $answers = $this->allAttending()['answers'];
        $answers[1] = ['guest_id' => $this->heidi->id, 'event_id' => $this->dinner->id, 'status' => 'declined'];

        $this->reply(['answers' => $answers])->assertRedirect();

        $this->assertSame(4, EventResponse::query()->count());
        $declined = $this->heidi->responses()->where('event_id', $this->dinner->id)->first();
        $this->assertSame(ResponseStatus::Declined, $declined->status);
        $this->assertNull($declined->menu_choice);
    }

    public function test_every_guest_needs_an_answer_for_every_event(): void
    {
        $answers = $this->allAttending()['answers'];
        array_pop($answers);

        $this->reply(['answers' => $answers])->assertSessionHasErrors('answers');
        $this->assertSame(0, EventResponse::query()->count());
    }

    public function test_menu_is_required_only_for_attending_guests_at_the_dinner(): void
    {
        $answers = $this->allAttending()['answers'];
        unset($answers[1]['menu']);

        $this->reply(['answers' => $answers])->assertSessionHasErrors('answers.1.menu');
    }

    public function test_menu_is_not_asked_when_the_couple_offers_none(): void
    {
        $this->wedding->update(['menu_options' => null, 'children_menu' => false]);
        $answers = array_map(fn (array $answer): array => array_diff_key($answer, ['menu' => true]), $this->allAttending()['answers']);

        $this->reply(['answers' => $answers])->assertSessionHasNoErrors();
        $this->assertSame(0, EventResponse::query()->whereNotNull('menu_choice')->count());
    }

    public function test_only_children_may_choose_the_childrens_menu_and_only_if_offered(): void
    {
        $answers = $this->allAttending()['answers'];
        $answers[1]['menu'] = 'children';
        $this->reply(['answers' => $answers])->assertSessionHasErrors('answers.1.menu');

        $this->wedding->update(['children_menu' => false]);
        $this->reply($this->allAttending())->assertSessionHasErrors('answers.3.menu');
    }

    public function test_allergies_are_encrypted_write_only_and_kept_when_left_blank(): void
    {
        $this->reply($this->allAttending(['guests' => [['id' => $this->heidi->id, 'dietary_notes' => 'Keine Nüsse']]]));

        $raw = DB::table('guests')->where('id', $this->heidi->id)->value('dietary_notes');
        $this->assertNotSame('Keine Nüsse', $raw);
        $this->assertSame('Keine Nüsse', $this->heidi->fresh()->dietary_notes);

        $this->reply($this->allAttending(['guests' => [['id' => $this->heidi->id, 'dietary_notes' => '']]]));
        $this->assertSame('Keine Nüsse', $this->heidi->fresh()->dietary_notes);

        $this->reply($this->allAttending(['guests' => [['id' => $this->heidi->id, 'clear_dietary' => true]]]));
        $this->assertNull($this->heidi->fresh()->dietary_notes);
    }

    public function test_plus_one_is_a_guest_who_joins_where_the_household_attends(): void
    {
        $this->household->update(['plus_one_allowed' => true]);

        $this->reply($this->allAttending(['plus_one' => ['first_name' => 'Marco', 'menu' => 'vegi']]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('invitation.show', $this->household));

        $marco = $this->household->guests()->where('is_plus_one', true)->sole();
        $this->assertSame('Marco', $marco->first_name);
        $this->assertSame(2, $marco->responses()->where('status', ResponseStatus::Attending)->count());
        $this->assertSame('vegi', $marco->responses()->where('event_id', $this->dinner->id)->value('menu_choice'));

        $this->reply($this->allAttending(['plus_one' => null]));
        $this->assertFalse($this->household->guests()->where('is_plus_one', true)->exists());
    }

    public function test_plus_one_is_refused_when_not_allowed(): void
    {
        $this->reply($this->allAttending(['plus_one' => ['first_name' => 'Marco', 'menu' => 'vegi']]))
            ->assertSessionHasErrors('plus_one');
    }

    public function test_household_extras_are_kept_only_when_the_couple_asks(): void
    {
        $extras = ['shuttle_seats' => 2, 'needs_stay' => true, 'song_wish' => 'September'];

        $this->reply($this->allAttending($extras));
        $this->assertNull($this->household->fresh()->shuttle_seats);
        $this->assertNull($this->household->fresh()->needs_stay);
        $this->assertSame('September', $this->household->fresh()->song_wish);

        $this->wedding->update(['offers_shuttle' => true, 'offers_stay' => true, 'asks_song' => false]);
        $this->reply($this->allAttending($extras));
        $this->assertSame(2, $this->household->fresh()->shuttle_seats);
        $this->assertTrue($this->household->fresh()->needs_stay);
    }

    public function test_foreign_guests_and_events_are_rejected(): void
    {
        $stranger = Guest::factory()->create();
        $notInvited = Event::factory()->for($this->wedding)->ofType(EventType::Brunch)->create();
        $answers = $this->allAttending()['answers'];
        $answers[0]['guest_id'] = $stranger->id;
        $answers[2]['event_id'] = $notInvited->id;

        $this->reply(['answers' => $answers])
            ->assertSessionHasErrors(['answers.0.guest_id', 'answers.2.event_id']);
    }

    public function test_replies_close_after_the_deadline(): void
    {
        $this->wedding->update(['rsvp_deadline' => now()->subDay()]);

        $this->reply($this->allAttending())->assertSessionHasErrors('deadline');
        $this->assertSame(0, EventResponse::query()->count());

        $this->get(route('invitation.reply', $this->household))
            ->assertInertia(fn (Assert $page) => $page->where('reply.open', false));
    }

    public function test_invitation_shows_the_saved_answer(): void
    {
        $this->reply($this->allAttending());

        $this->get(route('invitation.show', $this->household))
            ->assertInertia(fn (Assert $page) => $page
                ->where('invitation.reply.answered', true)
                ->where('invitation.reply.attending', 2)
                ->where('invitation.rsvpOpen', true)
            );
    }
}
