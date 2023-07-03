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
        $this->validate($request, [
            'subject' => ['required'],
        ], $request->all());

        $task = $project->tasks()->create([
            'subject' => $request->subject,
            'description' => $request->description,
            'author_id' => auth()->user()->id,
            'number' => rand(1, 99999),
            'private' => $request->private,
        ]);

        $this->message('success', 'Task created');

//        return to_route('project.tasks', ['project' => $project]);

        if ($request->get('open') == 1) {
            return to_route('project.task', ['project' => $project, 'task' => $task]);
        } else {
            return redirect($request->header('X-Inertia-Modal-Redirect'));
        }
    }
}
