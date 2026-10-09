<?php

namespace Tests\Feature\Admin;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class InquiriesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }

    public function test_only_admins_read_inquiries(): void
    {
        $inquiry = Inquiry::factory()->create();

        $this->get(route('admin.inquiries.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('admin.inquiries.index'))->assertForbidden();
        $this->actingAs(User::factory()->create())->patch(route('admin.inquiries.update', $inquiry), ['answered' => true])->assertForbidden();
    }

    public function test_admin_sees_open_inquiries_before_answered_ones(): void
    {
        Inquiry::factory()->create(['answered_at' => now()]);
        $open = Inquiry::factory()->create();

        $this->actingAs($this->admin())
            ->get(route('admin.inquiries.index'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('admin/Inquiries')
                ->has('inquiries', 2)
                ->where('inquiries.0.id', $open->id)
                ->where('inquiries.0.answered', false)
                ->where('adminInbox', 1));
    }

    public function test_admin_marks_an_inquiry_answered_and_open_again(): void
    {
        $inquiry = Inquiry::factory()->create();
        $admin = $this->admin();

        $this->actingAs($admin)->patch(route('admin.inquiries.update', $inquiry), ['answered' => true])->assertRedirect();
        $this->assertNotNull($inquiry->fresh()->answered_at);

        $this->actingAs($admin)->patch(route('admin.inquiries.update', $inquiry), ['answered' => false])->assertRedirect();
        $this->assertNull($inquiry->fresh()->answered_at);
    }

    public function test_couples_never_receive_the_inbox_count(): void
    {
        Inquiry::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertInertia(fn (Assert $page) => $page->where('adminInbox', null));
    }
}
