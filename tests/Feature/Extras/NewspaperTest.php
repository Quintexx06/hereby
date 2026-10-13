<?php

namespace Tests\Feature\Extras;

use App\Enums\Addon;
use App\Enums\Plan;
use App\Models\GuestPhoto;
use App\Models\NewspaperArticle;
use App\Models\ProgrammeItem;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class NewspaperTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake(GuestPhoto::DISK);
    }

    public function test_a_friend_sends_a_story_with_a_photo_through_the_link(): void
    {
        $wedding = $this->weddingWithNewspaper();
        $link = route('invitation.newspaper.show', $wedding->newspaperToken());

        $this->get($link)
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertInertia(fn (Assert $page) => $page
                ->component('invitation/Newspaper')
                ->has('translations.invitation.newspaper.title'));

        $this->post(route('invitation.newspaper.store', $wedding->newspaperToken()), [
            'author_name' => 'Lena',
            'title' => 'Wie alles in Island begann',
            'body' => 'Es war kalt, und Anna hatte die falschen Schuhe.',
            'photo' => UploadedFile::fake()->image('island.jpg', 1200, 800),
        ])->assertRedirect($link);

        $article = $wedding->newspaperArticles()->sole();
        $this->assertSame('Lena', $article->author_name);
        $this->assertNotNull($article->photo_path);
        Storage::disk(GuestPhoto::DISK)->assertExists((string) $article->photo_path);
        $this->assertStringStartsWith("weddings/{$wedding->id}/", (string) $article->photo_path);
    }

    public function test_the_link_exists_only_with_the_newspaper(): void
    {
        $wedding = $this->weddingWithNewspaper();
        $link = route('invitation.newspaper.show', $wedding->newspaperToken());
        $wedding->addons()->delete();

        $this->get($link)->assertNotFound();
        $this->get(route('invitation.newspaper.show', 'kein-token'))->assertNotFound();
    }

    public function test_couture_includes_the_newspaper(): void
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Couture]);

        $this->get(route('invitation.newspaper.show', $wedding->newspaperToken()))->assertOk();
    }

    public function test_the_witnesses_edit_and_print_the_newspaper(): void
    {
        $wedding = $this->weddingWithNewspaper();
        $token = $wedding->witnessToken();
        $path = UploadedFile::fake()->image('a.jpg')->store("weddings/{$wedding->id}/newspaper", GuestPhoto::DISK);
        $first = NewspaperArticle::factory()->for($wedding)->create(['title' => 'Der erste Kuss', 'photo_path' => $path]);
        $second = NewspaperArticle::factory()->for($wedding)->create(['title' => 'Das Schuhdrama']);
        $foreign = NewspaperArticle::factory()->for($this->weddingWithNewspaper())->create();

        $this->get(route('witness.show', $token))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('evening', false)
                ->has('articles', 2)
                ->where('articles.0.title', 'Der erste Kuss'));

        $this->get(route('witness.newspaper.photo', [$token, $first]))->assertOk();
        $this->delete(route('witness.newspaper.destroy', [$token, $foreign]))->assertNotFound();
        $this->delete(route('witness.newspaper.destroy', [$token, $second]))->assertRedirect();
        $this->assertModelMissing($second);

        $this->get(route('witness.newspaper.print', $token))
            ->assertOk()
            ->assertSee('Der erste Kuss')
            ->assertSee($wedding->couple_names)
            ->assertDontSee('Das Schuhdrama');
    }

    public function test_a_newspaper_without_atelier_opens_the_witness_link_but_not_the_evening(): void
    {
        $wedding = $this->weddingWithNewspaper();
        $item = ProgrammeItem::factory()->for($wedding)->create();

        $this->get(route('witness.show', $wedding->witnessToken()))->assertOk();
        $this->patch(route('witness.programme.update', [$wedding->witnessToken(), $item]), ['status' => 'accepted'])->assertNotFound();
    }

    public function test_the_couple_gets_the_links_but_not_the_stories(): void
    {
        $wedding = $this->weddingWithNewspaper();
        NewspaperArticle::factory()->for($wedding)->create(['title' => 'Streng geheim']);

        $this->actingAs($wedding->owner)
            ->get(route('weddings.newspaper.edit', $wedding))
            ->assertInertia(fn (Assert $page) => $page
                ->component('extras/Newspaper')
                ->where('unlocked', true)
                ->where('articles', 1)
                ->where('contributeLink', route('invitation.newspaper.show', $wedding->refresh()->newspaper_token))
                ->where('witnessLink', route('witness.show', $wedding->witness_token)))
            ->assertDontSee('Streng geheim');

        $without = Wedding::factory()->create(['plan' => Plan::Atelier]);
        $this->actingAs($without->owner)
            ->get(route('weddings.newspaper.edit', $without))
            ->assertInertia(fn (Assert $page) => $page->where('unlocked', false)->where('contributeLink', null));
    }

    public function test_the_newspaper_leaves_with_the_wedding(): void
    {
        $wedding = $this->weddingWithNewspaper();
        $path = UploadedFile::fake()->image('a.jpg')->store("weddings/{$wedding->id}/newspaper", GuestPhoto::DISK);
        NewspaperArticle::factory()->for($wedding)->create(['photo_path' => $path]);

        $wedding->delete();

        $this->assertSame(0, NewspaperArticle::query()->count());
        Storage::disk(GuestPhoto::DISK)->assertMissing($path);
    }

    private function weddingWithNewspaper(): Wedding
    {
        $wedding = Wedding::factory()->create(['plan' => Plan::Signature]);
        $wedding->addons()->make()->forceFill(['addon' => Addon::Newspaper])->save();

        return $wedding->refresh();
    }
}
