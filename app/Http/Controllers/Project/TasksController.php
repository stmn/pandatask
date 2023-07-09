<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;

class TasksController extends Controller
{
    public function index(Request $request, Project $project): Response
    {
        return Inertia::render('Project/Tasks', [
            'activeTab' => 'tasks',
            'search' => $request->get('search'),
            'projects' => fn() => Project::query()->get(),
            'project' => fn() => $project,
            'tasks' => Inertia::lazy(fn() => $project->tasks()
                ->search($request->get('search'))
                ->with(['priority', 'status', 'latestActivity.user'])
                ->withCommentsCount()
                ->latest('latest_activity_at')
                ->paginate($this->perPage()))
        ]);
    }

    public function create(Project $project): Modal
    {
        return Inertia::modal('Project/CreateTask', [
            'project' => $project,
            'statuses' => fn() => Status::all(),
            'priorities' => fn() => Priority::all(),
            'users' => fn() => $project->members()->get(),
        ])
            ->baseRoute('project.tasks', ['project' => $project]);
    }

    public function store(Project $project, Request $request): RedirectResponse
    {
        $request->validate([
            'task.assignees' => ['array'],
            'task.subject' => ['required', 'nullable'],
            'task.description' => [],
            'task.priority_id' => ['required', 'exists:priorities,id'],
            'task.status_id' => ['required', 'exists:statuses,id'],
            'task.private' => ['boolean'],
            'task.tags' => ['array'],
        ], $request->all());

        $task = $project->tasks()->create($request->get('task'));

        $this->success('Task created');

        if ($request->get('open') == 1) {
            return to_route('project.task', ['project' => $project, 'task' => $task]);
        }

        return redirect($request->header('X-Inertia-Modal-Redirect'));
    }
}
