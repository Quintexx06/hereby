<?php

namespace App\Actions\Partners;

use App\Enums\PartnerType;
use App\Mail\PartnerInvitation;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * The team opens a partner account (spec 2026-10-15-partner-accounts): the
 * partner, and one sign-in for its contact, who chooses a password by mail.
 */
class SetUpPartner
{
    /**
     * @param  array{type: string, name: string, email: string}  $data
     */
    public function handle(array $data): Partner
    {
        $user = DB::transaction(function () use ($data): User {
            $partner = Partner::query()->create([
                'type' => PartnerType::from($data['type']),
                'name' => $data['name'],
                'email' => Str::lower($data['email']),
            ]);

            $user = new User([
                'name' => $data['name'],
                'email' => Str::lower($data['email']),
                'password' => Hash::make(Str::random(40)),
            ]);
            $user->partner_id = $partner->id;
            $user->forceFill(['email_verified_at' => now()])->save();

            return $user;
        });

        $link = route('password.reset', ['token' => Password::createToken($user), 'email' => $user->email]);
        Mail::to($user->email)->queue(new PartnerInvitation($data['name'], $link));

        /** @var Partner */
        return $user->partner;
    }
}
