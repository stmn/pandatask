<?php

namespace App\Http\Controllers\Project;

use App\Enums\ActivityType;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TasksController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request, Project $project): Response
    {
        return Inertia::render('Project/Tasks', [
            'activeTab' => 'tasks',
            'search' => $request->get('search'),
            'projects' => fn() => Project::query()->get(),
            'project' => fn() => $project,
            'tasks' => Inertia::lazy(fn() => $project->tasks()
                ->search($request->get('search'))
                ->sortByString($request->get('sort'))
                ->latest('latest_activity_at')
//                ->withMax('latestActivity', 'created_at')
                ->with(['latestActivity.user', 'priority', 'status'])
//                ->withCount('comments')
                ->withCount(['activities as comments_count' => fn($query) => $query->where('type', '=', ActivityType::TASK_COMMENTED)])
                ->paginate($this->perPage()))
        ]);
    }

    public function create(\App\Models\Project $project, \Illuminate\Http\Request $request)
    {
        return Inertia::modal('Project/CreateTask', [
            'project' => $project,
            'statuses' => fn() => \App\Models\Status::all(),
            'priorities' => fn() => \App\Models\Priority::all(),
            'users' => fn() => $project->members()->get(),
//            'times' => fn() => $project->tasks()
//                ->with(['latestActivity.activity.author'])
//                ->when($request->has('search'), function ($query) use ($request) {
//                    $query->where('subject', 'like', '%' . $request->get('search') . '%');
//                })
//                ->paginate(20)
        ])
            ->baseRoute('project.tasks', ['project' => $project]);
    }

    public function store(\App\Models\Project $project, \Illuminate\Http\Request $request)
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

        $this->message('success', 'Task created');

//        return to_route('project.tasks', ['project' => $project]);

        if ($request->get('open') == 1) {
            return to_route('project.task', ['project' => $project, 'task' => $task]);
        } else {
            return redirect($request->header('X-Inertia-Modal-Redirect'));
        }
    }
}
