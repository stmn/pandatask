<?php

namespace App\Http\Controllers\Project;

use App\Enums\ActivityType;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
                ->with(['user', 'comment', 'media'])
                ->latest()
                ->get(),
            'users' => fn() => $project->members()->get(),
            'times' => fn() => $task->times()
                ->with(['author'])
                ->latest()
                ->get(),
            'statuses' => fn() => Status::all(),
            'priorities' => fn() => Priority::all(),
        ]);
    }

    public function update(Project $project, Request $request, Task $task)
    {
        $request->validate([
            'activity.comment' => [],
            'activity.files' => [],
            'activity.private' => [],
            'task.assignees' => ['array'],
            'task.subject' => ['required', 'sometimes'],
            'task.description' => [],
            'task.priority_id' => ['required', 'exists:priorities,id', 'sometimes'],
            'task.status_id' => ['required', 'exists:statuses,id', 'sometimes'],
            'task.private' => ['boolean'],
            'task.tags' => ['array'],
        ], $request->all());

        $activity = [
            'project_id' => $task->project_id,
            'type' => ActivityType::TASK_CHANGED,
            'details' => [
                'changed' => [],
                'attached' => [],
                'detached' => [],
            ],
            'private' => $request->get('private', false),
        ];

        if ($request->input('task')) {
            $original = $task->getOriginal();
            $data = $request->get('task');
            if($data['description'] === '<p></p>') {
                $data['description'] = str_replace("<p></p>", "", $data['description']);
            }
            $task->update($data);
            $changes = $task->getChanges();

            foreach ($changes as $key => $change) {
                $oldValues = $original[$key];
                $activity['details']['changed'][$key] = [$oldValues, $change];
            }
        }

        $commentBody = $request->collect('activity.comment')->first();
        $commentBody = $commentBody === '<p></p>' ? str_replace("<p></p>", "", $commentBody) : $commentBody;
        if ($commentBody) {
            $comment = Comment::create([
                'content' => $commentBody
            ]);

            $activity['comment_id'] = $comment->id;
            $activity['type'] = ActivityType::TASK_COMMENTED;
        }

        if ($request->has('task.assignees') && $request->task['assignees'] !== NULL) {
            $result = $task->assignees()->sync(
                collect($request->task['assignees'])->pluck('id')
            );
            if ($result['attached']) {
                $activity['details']['attached']['assignees'] = $result['attached'];
            }
            if ($result['detached']) {
                $activity['details']['detached']['assignees'] = $result['detached'];
            }
        }

        $activity = $task->activities()->create($activity);

        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                $activity->addMedia($file['raw'])->toMediaCollection('files');
            }
        }

        $this->message('success', 'Task updated successfully.');

//        return to_route('project.tasks', ['project' => $project]);
//        return to_route('project.task', ['project' => $project, 'task' => $task]);
    }
}
