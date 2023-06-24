<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
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
//        $query = Task::query()->usingSearchString("'WTF'");
//        dump(
//            $query->toSql(),
//        );
//        dd($query->get()->count());

        return Inertia::render('Project/Tasks', [
            'activeTab' => 'tasks',
            'search' => $request->get('search'),
            'projects' => Project::query()->get(),
            'project' => $project,
            'tasks' => $project->tasks()
                ->filter()
//                    ->usingSearchString($request->get('search'))//->dd()
//                ->usingSearchString()
                ->with(['latestActivity.author'])
                ->withCount('comments')
                ->when($request->has('search'), function ($query) use ($request) {
                    $query->where('subject', 'like', '%' . $request->get('search') . '%')
                          ->orWhere('number', 'like', $request->get('search') . '%');
                })
//                ->latest()
                ->paginate(5)
        ]);
    }

    public function create(\App\Models\Project $project, \Illuminate\Http\Request $request){
        return Inertia::modal('Project/CreateTask', [
            'project' => $project,
            'times' => $project->tasks()
                ->with(['latestActivity.activity.author'])
                ->when($request->has('search'), function ($query) use ($request) {
                    $query->where('subject', 'like', '%' . $request->get('search') . '%');
                })
                ->paginate(20)
        ])
            ->baseRoute('project.tasks', ['project' => $project]);
    }

    public function store(\App\Models\Project $project, \Illuminate\Http\Request $request){
        $task = $project->tasks()->create([
            'subject' => $request->subject,
            'description' => $request->description,
            'author_id' => auth()->user()->id,
            'number' => rand(1, 99999),
            'private' => $request->private,
        ]);

        // create task
        \App\Models\Activity::query()
            ->create([
                'project_id' => $task->project_id,
                'task_id' => $task->id,
                'type' => \App\Enums\ActivityType::TASK_CREATED,
                'activity_type' => \App\Models\Task::class,
                'activity_id' => $task->id,
                'private' => false,
            ]);

        // comment task
//    \App\Models\Activity::query()
//        ->create([
//            'task_id' => $task->id,
//            'type' => \App\Enums\ActivityType::TASK_COMMENTED,
//            'activity_type' => \App\Models\Comment::class,
//            'activity_id' => $comment->id,
//        ]);
//
//    // change task
//    \App\Models\Activity::query()
//        ->create([
//            'task_id' => $task->id,
//            'type' => \App\Enums\ActivityType::TASK_CHANGED,
//            'activity_type' => \App\Models\Change::class,
//            'activity_id' => $change->id,
//        ]);


        request()->session()->flash('message', [
            'type' => 'success',
            'message' => 'Task created'
        ]);

        if ($request->get('open') == 1) {
            return to_route('project.task', ['project' => $project, 'task' => $task]);
        }
    }
}
