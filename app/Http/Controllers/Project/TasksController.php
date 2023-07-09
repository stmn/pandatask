<?php

namespace App\Http\Controllers\Project;

use App\Enums\ActivityType;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class TasksController extends Controller
{
    public function index(Request $request, Project $project): Response
    {
        return Inertia::render('Project/Tasks', [
            'activeTab' => 'tasks',
            'search' => $request->get('search'),
            'projects' => fn() => Project::query()
                ->select('id', 'name')
                ->get(),
            'project' => fn() => $project,
            'tasks' => Inertia::lazy(fn() => $project->tasks()
                ->select('id', 'project_id', 'subject', 'description', 'priority_id', 'status_id', 'private', 'number', 'latest_activity_id')
                ->search($request->get('search'))
                ->sortByString($request->get('sort'))
                ->with(['priority', 'status', 'latestActivity.user'])
                ->withCommentsCount()
                ->latest('latest_activity_at')
                ->paginate($this->perPage()))
        ]);
    }

    public function show(Project $project, Task $task): Response
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

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(Request $request, Project $project, Task $task): void
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
            if (isset($data['description']) && $data['description'] === '<p></p>') {
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
        assert($activity instanceof Activity);

        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                $activity->addMedia($file['raw'])->toMediaCollection('files');
            }
        }

        $this->success('Task updated.');
    }
}
