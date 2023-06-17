<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request, Project $project, Task $task): Response
    {
        return Inertia::render('Project/Task', [
//        'activeTab' => 'tasks',
            'project' => $project,
            'task' => $task->load('author', 'project'),
            'activities' => $task->activities()->with([
                'activity.author'
            ])->latest()->get(),
            'times' => $task->times()
                ->with(['author'])
//            ->with(['latestActivity.activity.author'])
//            ->when($request->has('search'), function ($query) use ($request) {
//                $query->where('subject', 'like', '%'.$request->get('search').'%');
//            })
                ->orderByDesc('id')
                ->get()
        ]);
    }

    public function update(\App\Models\Project $project, \Illuminate\Http\Request $request, Task $task)
    {
        $request->validate([
            'comment' => ['required'],
        ], $request->all());

        $comment = $task->comments()->create([
            'content' => request()->get('comment'),
            'author_id' => 1,
            'private' => false,
        ]);

        \App\Models\Activity::query()
            ->create([
                'project_id' => $task->project_id,
                'task_id' => $task->id,
                'type' => \App\Enums\ActivityType::TASK_COMMENTED,
                'activity_type' => \App\Models\Comment::class,
                'activity_id' => $comment->id,
                'private' => false,
            ]);

        return to_route('project.task', ['project' => $project, 'task' => $task]);
    }
}
