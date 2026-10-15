<?php

namespace Tests\Feature\Partners;

use App\Models\Partner;
use App\Models\PartnerPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class VenuePhotosTest extends TestCase
{
    use RefreshDatabase;

    private function userFor(Partner $partner): User
    {
        $user = User::factory()->create();
        $user->partner_id = $partner->id;
        $user->save();

        return $user;
    }

    public function test_a_venue_uploads_photos_that_lead_its_page_and_the_tour_preview(): void
    {
        Storage::fake(PartnerPhoto::DISK);
        $venue = Partner::factory()->showcase()->create();
        $user = $this->userFor($venue);

        $this->actingAs($user)
            ->post(route('partner.profile.photos.store'), ['photo' => UploadedFile::fake()->image('steg.jpg', 2400, 1600)])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $photo = $venue->photos()->sole();
        $this->assertSame(1600, $photo->width);
        $this->get(route('partner-photos.show', $photo->token))->assertOk();

        $this->get(route('venues.show', $venue->slug))
            ->assertInertia(fn (Assert $page) => $page->where('venue.photos.0.url', $photo->url()));

        $this->actingAs($user)
            ->get(route('partner.preview', ['erste' => 'Lea', 'zweite' => 'Jonas', 'datum' => now()->addYear()->toDateString()]))
            ->assertInertia(fn (Assert $page) => $page->where('invitation.wedding.photos.0.url', $photo->url()));
    }

    public function test_photographers_have_no_venue_photos_and_venues_only_delete_their_own(): void
    {
        Storage::fake(PartnerPhoto::DISK);
        $photographer = $this->userFor(Partner::factory()->photographer()->create());

        $this->actingAs($photographer)
            ->post(route('partner.profile.photos.store'), ['photo' => UploadedFile::fake()->image('a.jpg')])
            ->assertForbidden();

        $venue = $this->userFor(Partner::factory()->create());
        $this->actingAs($venue)->post(route('partner.profile.photos.store'), ['photo' => UploadedFile::fake()->image('a.jpg')]);
        $photo = PartnerPhoto::query()->sole();

        $this->actingAs($this->userFor(Partner::factory()->create()))
            ->delete(route('partner.profile.photos.destroy', $photo))
            ->assertForbidden();

        $this->actingAs($venue)->delete(route('partner.profile.photos.destroy', $photo))->assertRedirect();
        $this->assertModelMissing($photo);
        Storage::disk(PartnerPhoto::DISK)->assertMissing($photo->path);
    }
}
