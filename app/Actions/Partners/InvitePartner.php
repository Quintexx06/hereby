<?php

namespace App\Actions\Partners;

use App\Mail\PartnerJoinInvitation;
use App\Models\Partner;
use App\Models\PartnerInvite;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * The couple invites their venue, photographer or planner by mail. A
 * partner already on Hereby is connected straight away; anyone else gets
 * a link that opens an account already connected to the wedding.
 */
class InvitePartner
{
    public function __construct(private ConnectPartner $connect) {}

    /**
     * @param  array{type: string, name: string, email: string}  $data
     */
    public function handle(Wedding $wedding, array $data): Partner|PartnerInvite
    {
        $email = Str::lower($data['email']);
        $user = User::query()->where('email', $email)->first();

        if ($user?->partner) {
            $this->connect->handle($wedding, $user->partner);

            return $user->partner;
        }

        if ($user) {
            throw ValidationException::withMessages(['email' => 'Diese Adresse gehört zu einem Paar-Konto. Fragt euren Partner nach einer anderen.']);
        }

        $invite = $wedding->partnerInvites()->updateOrCreate(
            ['email' => $email],
            ['type' => $data['type'], 'name' => $data['name']],
        );
        $invite->touch();

        Mail::to($email)->queue(new PartnerJoinInvitation($invite));

        return $invite;
    }
}
