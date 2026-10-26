<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Support\PersonalLink;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * No more reminders for this household. Opening the link from a reminder
 * only asks, because mail scanners open links on their own; the button, or
 * a mail app's one-click unsubscribe (a POST), stops them. Signed, so nobody
 * can switch them off for someone else.
 */
class StopRemindersController extends Controller
{
    public function __invoke(Request $request, Household $household): Response
    {
        if ($request->isMethod('post') && $household->reminders_opted_out_at === null) {
            $household->forceFill(['reminders_opted_out_at' => now()])->save();
        }

        $household->load('wedding');

        return Inertia::render('invitation/RemindersStopped', [
            'template' => $household->wedding->template,
            'couple' => $household->wedding->couple_names,
            'link' => PersonalLink::to($household),
            'stopped' => $household->reminders_opted_out_at !== null,
            'stopUrl' => $request->getRequestUri(),
        ]);
    }
}
