<?php /** @noinspection PhpUnusedParameterInspection PhpInconsistentReturnPointsInspection */

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    public function __construct()
    {
        //
    }

    public function delete(User $user, Group $group): bool
    {
        return !in_array($group->type, ['admin', 'client', 'team']);
    }
}
