<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wedding;

/**
 * A wedding belongs to the couple who created it. Roles for parents,
 * witnesses and planners come in Phase 2.
 */
class WeddingPolicy
{
    public function view(User $user, Wedding $wedding): bool
    {
        return $wedding->owner_id === $user->id;
    }

    public function update(User $user, Wedding $wedding): bool
    {
        return $wedding->owner_id === $user->id;
    }
}
