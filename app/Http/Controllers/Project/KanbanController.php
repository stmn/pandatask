<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Project;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class KanbanController extends ProjectController
{
    public function index(Request $request, Project $project): Response
    {
        return Inertia::render('Project/Kanban/Kanban', [
            'activeTab' => 'tasks',
            'search' => $request->get('search'),
            'projects' => fn() => Project::query()
                ->select('id', 'name')
                ->get(),
            'project' => fn() => $project,
            'tasks' => function() use ($project) {
                    $tasks = [];

                    foreach (Status::all() as $status){
                        $tasks[$status->id] = $this->tasks($project, $status);
                    }

                    return $tasks;
                }
        ]);
    }

    public function tasks(Project $project, Status $status): LengthAwarePaginator
    {
        return $project->tasks()
            ->select('id', 'project_id', 'subject', 'description', 'priority_id', 'status_id', 'private', 'number', 'latest_activity_id')
            ->search(request()->get('search'))
            ->sortByString(request()->get('sort'))
            ->with(['priority', 'status', 'latestActivity.user', 'assignees'])
            ->withCommentsCount()
            ->forCurrentUser()
            ->where('status_id', $status->id)
//            ->offset(request()->get('offset') ?? 0)
            ->latest('latest_activity_at')
            ->paginate(5);//$this->perPage());
    }
}
