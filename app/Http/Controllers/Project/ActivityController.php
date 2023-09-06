<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Project;

use App\Models\Activity;
use App\Models\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ActivityController extends ProjectController
{
    /**
     * @throws AuthorizationException
     */
    public function index(Project $project): Response
    {
        $this->authorize('viewAny', [Activity::class, $project]);

        return Inertia::render('Project/Activity/Activity', [
            'activeTab' => 'activity',
            'activities' => fn() => $project
                ->activities()
                ->select('id', 'project_id', 'task_id', 'user_id', 'comment_id', 'created_at', 'type', 'details', 'private')
                ->with(['task', 'user', 'comment', 'media'])
                ->forCurrentUser()
                ->latest()
                ->paginate($this->perPage())
            //->appends('details'),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(Project $project, Activity $activity)//: RedirectResponse
    {
        $this->authorize('delete', $activity);

        $activity->delete();

        $this->success('Activity deleted');

//        return redirect()->back();
    }

    public function private(Project $project, Activity $activity)
    {
        $activity->update([
            'private' => !$activity->private,
        ]);

    }
}
