<?php /** @noinspection PhpUnusedParameterInspection PhpInconsistentReturnPointsInspection */

namespace App\Policies;

use App\Models\Project;
use App\Models\Time;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimePolicy
{
    public function before()
    {
        if (loggedUser()->isAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user, Project $project): bool
    {
        return true;
    }

    public function create(User $user, Project $project): bool|Response
    {
        if ($user->isClient()) {
            return Response::deny('As a client you can not create time entries.');
        }

        return !$user->isClient() && $user->can('view', $project);
    }

    public function update(User $user, Time $time): bool
    {
        return $time->author->is(loggedUser());
    }

    public function delete(User $user, Time $time): bool
    {
        return $time->author->is(loggedUser());
    }
}
