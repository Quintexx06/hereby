<?php

namespace Tests\Feature\Weddings;

use App\Models\Household;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CouplePhotosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake(WeddingPhoto::DISK);
    }

    public function test_the_couple_uploads_a_photo_and_only_a_web_copy_is_kept(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->post(route('weddings.photos.store', $wedding), ['photo' => UploadedFile::fake()->image('wir.jpg', 4000, 3000)])
            ->assertRedirect();

        $photo = $wedding->couplePhotos()->sole();
        $this->assertStringStartsWith("weddings/{$wedding->id}/couple/", $photo->path);
        $this->assertSame([1600, 1200], [$photo->width, $photo->height]);
        $this->assertSame(40, strlen($photo->token));

        [$width, $height] = (array) getimagesizefromstring((string) Storage::disk(WeddingPhoto::DISK)->get($photo->path));
        $this->assertSame([1600, 1200], [$width, $height]);
        $this->assertCount(1, Storage::disk(WeddingPhoto::DISK)->allFiles("weddings/{$wedding->id}"));
    }

    public function test_only_images_and_at_most_four_photos(): void
    {
        $wedding = Wedding::factory()->create();
        WeddingPhoto::factory()->for($wedding)->count(3)->create();
        $route = route('weddings.photos.store', $wedding);

        $this->actingAs($wedding->owner)
            ->post($route, ['photo' => UploadedFile::fake()->create('film.mp4', 50, 'video/mp4')])
            ->assertSessionHasErrors('photo');
        $this->actingAs($wedding->owner)
            ->post($route, ['photo' => UploadedFile::fake()->image('vier.jpg')])
            ->assertSessionHasNoErrors();
        $this->actingAs($wedding->owner)
            ->post($route, ['photo' => UploadedFile::fake()->image('fuenf.jpg')])
            ->assertSessionHasErrors('photo');

        $this->assertSame(4, $wedding->couplePhotos()->count());
    }

    public function test_only_the_couple_manages_their_photos(): void
    {
        $wedding = Wedding::factory()->create();
        $photo = WeddingPhoto::factory()->for($wedding)->create();
        $stranger = User::factory()->create();

        $this->actingAs($stranger)
            ->post(route('weddings.photos.store', $wedding), ['photo' => UploadedFile::fake()->image('x.jpg')])
            ->assertForbidden();
        $this->actingAs($stranger)->delete(route('weddings.photos.destroy', [$wedding, $photo]))->assertForbidden();

        $other = Wedding::factory()->create();
        $this->actingAs($other->owner)->delete(route('weddings.photos.destroy', [$other, $photo]))->assertNotFound();
        $this->assertModelExists($photo);
    }

    public function test_any_photo_can_become_the_cover_and_removing_one_deletes_its_file(): void
    {
        $wedding = Wedding::factory()->create();
        [$first, $second, $third] = WeddingPhoto::factory()->for($wedding)->count(3)->sequence(
            ['position' => 0], ['position' => 1], ['position' => 2],
        )->create();

        Storage::disk(WeddingPhoto::DISK)->put($first->path, 'jpeg');

        $this->actingAs($wedding->owner)->put(route('weddings.photos.cover', [$wedding, $third]))->assertRedirect();
        $this->assertSame([$third->id, $first->id, $second->id], $wedding->couplePhotos()->pluck('id')->all());

        $this->actingAs($wedding->owner)->delete(route('weddings.photos.destroy', [$wedding, $first]))->assertRedirect();
        $this->assertModelMissing($first);
        Storage::disk(WeddingPhoto::DISK)->assertMissing($first->path);
    }

    public function test_photos_are_served_by_their_token_and_kept_out_of_search(): void
    {
        $wedding = Wedding::factory()->create();
        $photo = WeddingPhoto::factory()->for($wedding)->create();
        Storage::disk(WeddingPhoto::DISK)->put($photo->path, 'jpeg');

        $this->get(route('wedding-photos.show', $photo->token))
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get(route('wedding-photos.show', str_repeat('x', 40)))->assertNotFound();
    }

    public function test_guests_and_the_preview_get_the_photos_in_order(): void
    {
        $wedding = Wedding::factory()->create();
        $cover = WeddingPhoto::factory()->for($wedding)->create(['position' => 0, 'width' => 1600, 'height' => 1067]);
        WeddingPhoto::factory()->for($wedding)->create(['position' => 1]);
        $household = Household::factory()->for($wedding)->create();

        $this->get(route('invitation.show', $household))
            ->assertInertia(fn (Assert $page) => $page
                ->has('invitation.wedding.photos', 2)
                ->where('invitation.wedding.photos.0.url', route('wedding-photos.show', $cover->token))
                ->where('invitation.wedding.photos.0.width', 1600));

        $this->actingAs($wedding->owner)
            ->get(route('weddings.content.index', $wedding))
            ->assertInertia(fn (Assert $page) => $page->has('photos', 2)->where('photos.0.id', $cover->id));
    }

    public function test_photos_leave_with_the_wedding(): void
    {
        $wedding = Wedding::factory()->create();
        $photo = WeddingPhoto::factory()->for($wedding)->create();
        Storage::disk(WeddingPhoto::DISK)->put($photo->path, 'jpeg');

        $wedding->delete();

        $this->assertModelMissing($photo);
        Storage::disk(WeddingPhoto::DISK)->assertMissing($photo->path);
    }
}
