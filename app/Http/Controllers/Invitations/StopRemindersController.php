<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Models\Household;
use Inertia\Inertia;
use Inertia\Response;

/**
 * One click from a reminder: no more reminders for this household.
 * Signed, so nobody can switch them off for someone else.
 */
class StopRemindersController extends Controller
{
    public function __invoke(Household $household): Response
    {
        if ($household->reminders_opted_out_at === null) {
            $household->forceFill(['reminders_opted_out_at' => now()])->save();
        }

        $household->load('wedding');

        return Inertia::render('invitation/RemindersStopped', [
            'theme' => $household->wedding->theme,
            'couple' => $household->wedding->couple_names,
            'link' => route('invitation.show', $household),
        ]);
    }
}
