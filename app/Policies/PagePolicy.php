<?php /** @noinspection PhpUnusedParameterInspection PhpInconsistentReturnPointsInspection */

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class PagePolicy
{
    public function __construct()
    {
        //
    }

    public function viewAny(User $user, Project $project): bool
    {
        return true;
    }

    public function create(User $user, Project $project): bool
    {
        return !$user->isClient() && $user->can('view', $project);
    }

    public function update(User $user, Project $project): bool
    {
        return !$user->isClient() && $user->can('view', $project);
    }

    public function delete(User $user, Project $project): bool
    {
        return !$user->isClient() && $user->can('view', $project);
    }
}
