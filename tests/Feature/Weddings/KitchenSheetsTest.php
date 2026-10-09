<?php

namespace Tests\Feature\Weddings;

use App\Enums\EventType;
use App\Enums\ResponseStatus;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Household;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class KitchenSheetsTest extends TestCase
{
    use RefreshDatabase;

    private Wedding $wedding;

    private Event $dinner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->wedding = Wedding::factory()->create([
            'menu_options' => [['key' => 'fleisch', 'label' => 'Kalbsfilet'], ['key' => 'vegi', 'label' => 'Risotto']],
            'children_menu' => true,
        ]);
        $this->dinner = Event::factory()->for($this->wedding)->ofType(EventType::Dinner)->create();

        $meier = Household::factory()->for($this->wedding)->create(['name' => 'Familie Meier']);
        $meier->events()->attach($this->dinner);
        $heidi = Guest::factory()->for($meier)->create(['first_name' => 'Heidi', 'dietary_notes' => 'Keine Haselnüsse']);
        $lina = Guest::factory()->for($meier)->child()->create(['first_name' => 'Lina']);
        $heidi->responses()->create(['event_id' => $this->dinner->id, 'status' => ResponseStatus::Attending, 'menu_choice' => 'vegi']);
        $lina->responses()->create(['event_id' => $this->dinner->id, 'status' => ResponseStatus::Attending, 'menu_choice' => 'children']);

        $rossi = Household::factory()->for($this->wedding)->create(['name' => 'Rossi']);
        $rossi->events()->attach($this->dinner);
        Guest::factory()->for($rossi)->create(['first_name' => 'Marco']);
    }

    public function test_page_counts_people_menus_and_children_without_allergy_text(): void
    {
        $response = $this->actingAs($this->wedding->owner)->get(route('weddings.kitchen.index', $this->wedding));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('kitchen/Index')
            ->where('events.0.attending', 2)
            ->where('events.0.children', 1)
            ->where('events.0.pending', 1)
            ->where('events.0.menus', [['label' => 'Kindermenü', 'count' => 1], ['label' => 'Kalbsfilet', 'count' => 0], ['label' => 'Risotto', 'count' => 1]])
            ->where('allergies', 1)
        );
        $this->assertStringNotContainsString('Haselnüsse', $response->getContent());
    }

    public function test_printable_sheet_lists_allergies_for_the_owner_only(): void
    {
        $this->actingAs($this->wedding->owner)
            ->get(route('weddings.kitchen.sheet', $this->wedding))
            ->assertOk()
            ->assertSee('Keine Haselnüsse')
            ->assertSee('Familie Meier')
            ->assertHeader('Cache-Control', 'no-store, private');

        $this->actingAs(User::factory()->create())
            ->get(route('weddings.kitchen.sheet', $this->wedding))
            ->assertForbidden();
    }

    public function test_csv_has_one_row_per_person_and_opens_in_excel(): void
    {
        $response = $this->actingAs($this->wedding->owner)->get(route('weddings.kitchen.csv', $this->wedding));

        $response->assertOk()->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $csv = $response->streamedContent();

        $this->assertStringStartsWith("\u{FEFF}", $csv);
        $lines = array_values(array_filter(explode("\n", trim(substr($csv, 3)))));
        $this->assertCount(4, $lines);
        $this->assertStringContainsString(';', $lines[0]);
        $this->assertStringContainsString('Keine Haselnüsse', $csv);
        $this->assertStringContainsString('Risotto', $csv);
    }
}
