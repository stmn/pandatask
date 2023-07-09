<?php

namespace App\Policies;

use App\Models\User;

class StatusPolicy
{
    public function __construct()
    {
        //
    }

    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function delete(User $user): bool
    {
        return false;
    }
}
