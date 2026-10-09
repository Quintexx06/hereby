<?php

namespace App\Actions\Admin;

use App\Actions\Weddings\StartWeddingSetup;
use App\Mail\CoupleInvitation;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * "Set up by hand within 48 hours" (positioning, roadmap 1.1): the team opens
 * an account and a draft wedding for a couple, then the couple gets a link to
 * choose their own password. The team continues on the couple's own pages.
 */
class SetUpCouple
{
    public function __construct(private StartWeddingSetup $start) {}

    /**
     * @param  array{email: string, partner_one: string, partner_two: string}  $data
     */
    public function handle(array $data): Wedding
    {
        $wedding = DB::transaction(function () use ($data): Wedding {
            $couple = new User([
                'name' => "{$data['partner_one']} & {$data['partner_two']}",
                'email' => Str::lower($data['email']),
                'password' => Hash::make(Str::random(40)),
            ]);
            $couple->forceFill(['email_verified_at' => now()])->save();

            $wedding = $this->start->handle($couple);
            $wedding->update([
                'partner_one' => $data['partner_one'],
                'partner_two' => $data['partner_two'],
                'couple_names' => "{$data['partner_one']} & {$data['partner_two']}",
            ]);

            return $wedding;
        });

        $couple = $wedding->owner;
        $link = route('password.reset', ['token' => Password::broker()->createToken($couple), 'email' => $couple->email]);
        Mail::to($couple->email)->queue(new CoupleInvitation($wedding->couple_names, $link));

        return $wedding;
    }
}
