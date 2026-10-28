<?php

namespace Tests\Feature;

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ErrorPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_error_after_a_click_in_the_app_is_a_page_with_working_links(): void
    {
        $wedding = Wedding::factory()->create();
        $version = (string) app(HandleInertiaRequests::class)->version(Request::create('/'));

        $this->actingAs(User::factory()->create())
            ->get(route('weddings.guests.index', $wedding), ['X-Inertia' => 'true', 'X-Inertia-Version' => $version])
            ->assertForbidden()
            ->assertJsonPath('component', 'ErrorPage')
            ->assertJsonPath('props.status', 403)
            ->assertJsonPath('props.title', 'Hier geht es nicht weiter.')
            ->assertJsonPath('props.reload', null);
    }

    public function test_a_full_page_load_keeps_the_plain_error_page(): void
    {
        $this->get('/gibt-es-nicht')
            ->assertNotFound()
            ->assertSee('Zur Startseite');
    }
}
