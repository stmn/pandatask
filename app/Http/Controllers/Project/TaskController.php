<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Comment;
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
            'project' => $project,
            'task' => $task->load('author', 'project', 'assignees'),
            'activities' => fn() => $task->activities()
                ->with(['user', 'comments', 'media'])
                ->latest()
                ->get(),
            'users' => fn() => $project->members()->get(),
            'times' => fn() => $task->times()
                ->with(['author'])
                ->latest()
                ->get()
        ]);
    }

    public function update(\App\Models\Project $project, \Illuminate\Http\Request $request, Task $task)
    {
        $request->validate([
            'comment' => [],
            'files' => [],
            'private' => [],
            'assignees' => [],

            'task.priority_id' => [],
            'task.status_id' => [],
        ], $request->all());
//dd($task->number,$request->input('task'));
        $task->update($request->input('task'));

        $activity = Activity::query()
            ->create([
                'user_id' => $request->user()->id,
                'project_id' => $task->project_id,
                'task_id' => $task->id,
                'changes' => ['priority_id' => [1, 2]],
                'private' => false,
            ]);

        if ($request->comment) {
            $comment = new Comment([
                'content' => $request->comment,
            ]);

//        if($request->comment || $request->files){
            $activity = Activity::query()
                ->create([
                    'user_id' => $request->user()->id,
                    'project_id' => $task->project_id,
                    'task_id' => $task->id,
                    'private' => $request->private,
//                    'type' => \App\Enums\ActivityType::TASK_COMMENTED,
//                    'activity_type' => \App\Models\Comment::class,
//                    'activity_id' => $comment->id,
                ]);
//        }
            assert($activity instanceof Activity);

            $activity->comment()->save($comment);
        }

        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                $activity->addMedia($file['raw'])->toMediaCollection('files');
            }
        }

        if ($request->assignees !== NULL) {
            $result = $task->assignees()->sync(
                collect($request->assignees)->pluck('id')
            );
        }

        if (0) {
            if ($result['attached'] || $result['detached']) {
                $activity = Activity::query()
                    ->create([
                        'user_id' => $request->user()->id,
                        'project_id' => $task->project_id,
                        'task_id' => $task->id,
                        'private' => $request->private,

                        'action' => '', // attached, detached, changed, commented
                        'field' => '', // assignees, status, priority, null
                        'meta' => [],

//                    'type' => \App\Enums\ActivityType::TASK_CHANGED,
//                    'activity_type' => \App\Models\Comment::class,
//                    'activity_id' => $comment->id,
                        'changes' => [
                            [
                                'field' => 'assignees',
                                'from' => $result['detached'],
                                'text' => 'changed :field from :old to :new',
                            ]
                        ]
                    ]);
            }
        }

//        return to_route('project.task', ['project' => $project, 'task' => $task]);
    }
}
