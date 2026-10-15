<?php

namespace Tests\Feature\Bespoke;

use App\Actions\Billing\MarkAddonPaid;
use App\Enums\Addon;
use App\Enums\BriefStatus;
use App\Enums\Plan;
use App\Mail\BespokeBriefSubmitted;
use App\Models\BriefPhoto;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BespokeBriefTest extends TestCase
{
    use RefreshDatabase;

    private function brief(): array
    {
        return [
            'wishes' => 'Leicht, viel Weiss, ein Hauch Gold. Unbedingt das Foto vom Steg.',
            'colors' => ['#F4E9DC', '#B08D57'],
            'mood' => 'Sommerfest am See',
            'links' => 'https://pinterest.com/annaluca',
            'ready_by' => now()->addMonths(2)->toDateString(),
        ];
    }

    public function test_a_couple_writes_a_brief_and_sends_it_once_booked(): void
    {
        Mail::fake();
        config(['hereby.inbox' => 'team@hereby.ch']);
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)->put(route('weddings.bespoke.update', $wedding), $this->brief())->assertSessionHasNoErrors();
        $this->actingAs($wedding->owner)->post(route('weddings.bespoke.submit', $wedding))->assertSessionHasErrors('brief');

        app(MarkAddonPaid::class)->handle($wedding, Addon::Bespoke, 'cs_test_1');
        $this->actingAs($wedding->owner)->post(route('weddings.bespoke.submit', $wedding))->assertSessionHasNoErrors();

        $brief = $wedding->designBrief()->sole();
        $this->assertSame(BriefStatus::Submitted, $brief->status);
        $this->assertSame(['#f4e9dc', '#b08d57'], $brief->colors);
        Mail::assertQueued(BespokeBriefSubmitted::class, fn (BespokeBriefSubmitted $mail): bool => $mail->hasTo('team@hereby.ch'));
    }

    public function test_couture_includes_it_and_the_team_moves_it_along(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Couture]);
        $this->actingAs($wedding->owner)->put(route('weddings.bespoke.update', $wedding), $this->brief());
        $this->actingAs($wedding->owner)->post(route('weddings.bespoke.submit', $wedding))->assertSessionHasNoErrors();

        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $brief = $wedding->designBrief()->sole();

        $this->actingAs($admin)->get(route('admin.briefs.index'))
            ->assertInertia(fn (Assert $page) => $page->component('admin/Briefs')->where('briefs.0.colors', ['#f4e9dc', '#b08d57']));
        $this->actingAs($admin)
            ->patch(route('admin.briefs.update', $brief), ['status' => 'in_progress', 'team_note' => 'Erster Entwurf am Freitag.'])
            ->assertRedirect();

        $this->actingAs($wedding->owner)->get(route('weddings.bespoke.show', $wedding))
            ->assertInertia(fn (Assert $page) => $page
                ->where('brief.status', 'in_progress')
                ->where('brief.team_note', 'Erster Entwurf am Freitag.')
                ->where('wedding.owned', true)
            );
    }

    public function test_inspiration_photos_are_private_to_the_couple_and_the_team(): void
    {
        Storage::fake(BriefPhoto::DISK);
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->post(route('weddings.bespoke.photos.store', $wedding), ['photo' => UploadedFile::fake()->image('kleid.jpg', 1200, 1600)])
            ->assertSessionHasNoErrors();
        $photo = BriefPhoto::query()->sole();

        $this->actingAs($wedding->owner)->get(route('weddings.bespoke.photos.show', [$wedding, $photo]))->assertOk();
        $this->actingAs(Wedding::factory()->create()->owner)->get(route('weddings.bespoke.photos.show', [$wedding, $photo]))->assertForbidden();
    }
}
