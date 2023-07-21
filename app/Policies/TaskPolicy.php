<?php /** @noinspection PhpUnusedParameterInspection PhpInconsistentReturnPointsInspection */

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function before()
    {
        if (loggedUser()->isAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        if ($user->isClient()) {
            return !$task->private;
        }

        return $user->can('view', $task->project);
    }

    public function create(User $user, Project $project): bool
    {
        return $user->can('view', $project);
    }

    public function update(User $user, Task $task, Project $project): bool
    {
        return $user->can('view', $project);
    }
}
