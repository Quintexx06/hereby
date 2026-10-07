<?php

namespace Tests\Feature;

use App\Mail\InquiryReceived;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StoreInquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_couple_can_ask_a_question_from_the_landing_page(): void
    {
        Mail::fake();
        config(['hereby.inbox' => 'team@example.com']);

        $this->from(route('home'))
            ->post(route('inquiries.store'), [
                'email' => 'anna@example.com',
                'question' => 'Könnt ihr auch eine Hochzeit im Tessin abbilden?',
            ])
            ->assertRedirect(route('home'))
            ->assertInertiaFlash('toast.type', 'success');

        $inquiry = Inquiry::sole();
        $this->assertSame('anna@example.com', $inquiry->email);
        $this->assertNull($inquiry->answered_at);
        Mail::assertQueued(InquiryReceived::class, fn (InquiryReceived $mail) => $mail->hasTo('team@example.com') && $mail->inquiry->is($inquiry));
    }

    public function test_it_needs_a_reply_address_and_a_real_question(): void
    {
        $this->post(route('inquiries.store'), ['email' => 'nope', 'question' => 'Hi'])
            ->assertSessionHasErrors(['email', 'question']);

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_bots_filling_the_honeypot_are_rejected(): void
    {
        $this->post(route('inquiries.store'), [
            'email' => 'bot@example.com',
            'question' => 'Buy cheap followers now, best prices guaranteed!',
            'website' => 'https://spam.example',
        ])->assertSessionHasErrors('website');

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_no_mail_is_sent_when_no_inbox_is_configured(): void
    {
        Mail::fake();
        config(['hereby.inbox' => null]);

        $this->post(route('inquiries.store'), [
            'email' => 'anna@example.com',
            'question' => 'Wie viele Sprachen sind möglich?',
        ]);

        $this->assertDatabaseCount('inquiries', 1);
        Mail::assertNothingQueued();
    }
}
