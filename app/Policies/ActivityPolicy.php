<?php /** @noinspection PhpUnusedParameterInspection PhpInconsistentReturnPointsInspection */

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ActivityPolicy
{
    public function __construct()
    {
        //
    }

    public function viewAny(User $user, Project $project): bool
    {
        return true;
    }
}
