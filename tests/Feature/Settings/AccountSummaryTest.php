<?php

namespace Tests\Feature\Settings;

use App\Models\Guest;
use App\Models\Household;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AccountSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_pages_show_when_the_wedding_data_is_deleted(): void
    {
        config(['hereby.retention_months' => 12]);
        $wedding = Wedding::factory()->create(['wedding_date' => '2027-06-19']);
        Guest::factory()->count(3)->for(Household::factory()->for($wedding))->create();

        $this->actingAs($wedding->owner)
            ->get(route('profile.edit'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('account.wedding.deletes_on', '2028-06-19')
                ->where('account.wedding.guests', 3)
                ->where('account.two_factor', false)
                ->where('account.passkeys', 0)
            );
    }

    public function test_other_pages_do_not_compute_it(): void
    {
        $wedding = Wedding::factory()->create();

        $this->actingAs($wedding->owner)
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page->where('account', null));
    }
}
