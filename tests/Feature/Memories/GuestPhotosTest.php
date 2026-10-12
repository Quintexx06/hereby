<?php

namespace Tests\Feature\Memories;

use App\Enums\Plan;
use App\Jobs\MakePhotoThumbnail;
use App\Models\GuestPhoto;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class GuestPhotosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake(GuestPhoto::DISK);
    }

    public function test_a_guest_sends_a_photo_through_the_upload_link_without_an_account(): void
    {
        Queue::fake();
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $link = route('invitation.memories.show', $wedding->memoriesToken());

        $this->get($link)
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertInertia(fn (Assert $page) => $page
                ->component('invitation/Memories')
                ->where('wedding.coupleNames', $wedding->couple_names)
                ->has('translations.memories.title'));

        $this->post($link, ['photo' => UploadedFile::fake()->image('abend.jpg', 1200, 800)])->assertCreated();

        $photo = $wedding->photos()->sole();
        Storage::disk(GuestPhoto::DISK)->assertExists($photo->path);
        $this->assertStringStartsWith("weddings/{$wedding->id}/photos/", $photo->path);
        Queue::assertPushed(MakePhotoThumbnail::class);
    }

    public function test_the_upload_link_exists_only_with_a_plan_that_includes_memories(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $link = route('invitation.memories.show', $wedding->memoriesToken());
        $wedding->forceFill(['plan' => Plan::Signature])->save();

        $this->get($link)->assertNotFound();
        $this->post($link, ['photo' => UploadedFile::fake()->image('abend.jpg')])->assertNotFound();
        $this->get(route('invitation.memories.show', 'not-a-token'))->assertNotFound();
        $this->assertSame(0, GuestPhoto::query()->count());
    }

    public function test_only_photos_within_the_limits_are_kept(): void
    {
        Queue::fake();
        config(['hereby.memories.max_kilobytes' => 100, 'hereby.memories.max_photos' => 1]);
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $link = route('invitation.memories.show', $wedding->memoriesToken());

        $this->postJson($link, ['photo' => UploadedFile::fake()->create('film.mp4', 50, 'video/mp4')])->assertJsonValidationErrors('photo');
        $this->postJson($link, ['photo' => UploadedFile::fake()->image('gross.jpg')->size(200)])->assertJsonValidationErrors('photo');
        $this->postJson($link, ['photo' => UploadedFile::fake()->image('eins.jpg')])->assertCreated();
        $this->postJson($link, ['photo' => UploadedFile::fake()->image('zwei.jpg')])->assertJsonValidationErrors('photo');

        $this->assertSame(1, $wedding->photos()->count());
    }

    public function test_the_gallery_gets_a_small_upright_copy_of_each_photo(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $path = UploadedFile::fake()->image('abend.jpg', 2400, 1600)->store("weddings/{$wedding->id}/photos", GuestPhoto::DISK);
        $photo = GuestPhoto::factory()->for($wedding)->create(['path' => $path]);

        (new MakePhotoThumbnail($photo))->handle();

        $thumbnail = (string) $photo->refresh()->thumbnail_path;
        [$width, $height] = (array) getimagesizefromstring((string) Storage::disk(GuestPhoto::DISK)->get($thumbnail));
        $this->assertSame([MakePhotoThumbnail::EDGE, 533], [$width, $height]);
    }

    public function test_the_couple_sees_their_photos_and_the_link_to_share(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $path = UploadedFile::fake()->image('abend.jpg')->store("weddings/{$wedding->id}/photos", GuestPhoto::DISK);
        $photo = GuestPhoto::factory()->for($wedding)->create(['path' => $path]);

        $this->actingAs($wedding->owner)
            ->get(route('weddings.memories.index', $wedding))
            ->assertInertia(fn (Assert $page) => $page
                ->component('memories/Index')
                ->where('unlocked', true)
                ->where('uploadLink', route('invitation.memories.show', $wedding->refresh()->memories_token))
                ->where('total', 1)
                ->where('photos.0.id', $photo->id));

        $this->actingAs($wedding->owner)
            ->get(route('weddings.memories.photo', [$wedding, $photo, 'klein']))
            ->assertOk()
            ->assertHeader('Cache-Control', 'max-age=3600, private');
    }

    public function test_without_atelier_the_couple_sees_the_offer_and_no_link(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Signature]);
        $photo = GuestPhoto::factory()->for($wedding)->create();

        $this->actingAs($wedding->owner)
            ->get(route('weddings.memories.index', $wedding))
            ->assertInertia(fn (Assert $page) => $page
                ->where('unlocked', false)
                ->where('uploadLink', null)
                ->where('photos', []));

        $this->actingAs($wedding->owner)
            ->get(route('weddings.memories.photo', [$wedding, $photo, 'original']))
            ->assertForbidden();
        $this->assertNull($wedding->refresh()->memories_token);
    }

    public function test_a_photo_of_another_wedding_is_out_of_reach(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $other = GuestPhoto::factory()->for(Wedding::factory()->create(['plan' => Plan::Atelier]))->create();

        $this->actingAs($wedding->owner)
            ->get(route('weddings.memories.photo', [$wedding, $other, 'original']))
            ->assertNotFound();
        $this->actingAs($wedding->owner)
            ->delete(route('weddings.memories.destroy', [$wedding, $other]))
            ->assertNotFound();
        $this->assertModelExists($other);
    }

    public function test_deleting_a_photo_removes_its_files(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $photo = $this->storedPhoto($wedding);

        $this->actingAs($wedding->owner)
            ->delete(route('weddings.memories.destroy', [$wedding, $photo]))
            ->assertRedirect();

        $this->assertModelMissing($photo);
        Storage::disk(GuestPhoto::DISK)->assertMissing([$photo->path, (string) $photo->thumbnail_path]);
    }

    public function test_photos_leave_the_server_with_the_wedding_and_with_the_account(): void
    {
        $expired = Wedding::factory()->heldMonthsAgo(13)->create(['plan' => Plan::Atelier]);
        $closing = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $photos = [$this->storedPhoto($expired), $this->storedPhoto($closing)];

        $this->artisan('model:prune', ['--model' => Wedding::class])->assertSuccessful();
        $closing->owner->delete();

        foreach ($photos as $photo) {
            $this->assertModelMissing($photo);
            Storage::disk(GuestPhoto::DISK)->assertMissing([$photo->path, (string) $photo->thumbnail_path]);
        }
    }

    private function storedPhoto(Wedding $wedding): GuestPhoto
    {
        $directory = GuestPhoto::directoryFor($wedding->id);

        return GuestPhoto::factory()->for($wedding)->create([
            'path' => UploadedFile::fake()->image('abend.jpg')->store("{$directory}/photos", GuestPhoto::DISK),
            'thumbnail_path' => UploadedFile::fake()->image('klein.jpg')->store("{$directory}/thumbs", GuestPhoto::DISK),
        ]);
    }
}
