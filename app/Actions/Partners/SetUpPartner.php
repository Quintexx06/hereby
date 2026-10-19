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
 * Opens a partner account (spec 2026-10-15-partner-accounts): the partner,
 * and one sign-in for its contact. Opened by the team, the contact chooses
 * a password by mail; opened from a couple's invite, they chose it already.
 */
class SetUpPartner
{
    /**
     * @param  array{type: string, name: string, email?: string|null, town?: string|null, without_login?: bool}  $data
     */
    public function handle(array $data): Partner
    {
        // A prospect we pitch to: no sign-in and no mail until it says yes.
        if (($data['without_login'] ?? false) || empty($data['email'])) {
            return Partner::query()->create([
                'type' => PartnerType::from($data['type']),
                'name' => $data['name'],
                'email' => isset($data['email']) ? Str::lower($data['email']) : null,
                'town' => $data['town'] ?? null,
            ]);
        }

        $user = $this->open(['type' => $data['type'], 'name' => $data['name'], 'email' => $data['email']], Str::random(40));
        $user->partner?->update(['town' => $data['town'] ?? null]);
        $this->sendInvitation($user, $data['name']);

        /** @var Partner */
        return $user->partner;
    }

    /**
     * Gives a prospect that said yes its sign-in, at its contact address.
     */
    public function invite(Partner $partner, string $email): User
    {
        $user = new User(['name' => $partner->name, 'email' => Str::lower($email), 'password' => Hash::make(Str::random(40))]);
        $user->partner_id = $partner->id;
        $user->forceFill(['email_verified_at' => now()])->save();
        $this->sendInvitation($user, $partner->name);

        return $user;
    }

    public function sendInvitation(User $user, string $name): void
    {
        $link = route('password.reset', ['token' => Password::createToken($user), 'email' => $user->email]);
        Mail::to($user->email)->queue(new PartnerInvitation($name, $link));
    }

    /**
     * @param  array{type: string, name: string, email: string}  $data
     */
    public function open(array $data, string $password): User
    {
        return DB::transaction(function () use ($data, $password): User {
            $partner = Partner::query()->create([
                'type' => PartnerType::from($data['type']),
                'name' => $data['name'],
                'email' => Str::lower($data['email']),
            ]);

            $user = new User([
                'name' => $data['name'],
                'email' => Str::lower($data['email']),
                'password' => Hash::make($password),
            ]);
            $user->partner_id = $partner->id;
            $user->forceFill(['email_verified_at' => now()])->save();

            return $user;
        });
    }
}
