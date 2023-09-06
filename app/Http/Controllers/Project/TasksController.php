<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Project;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\Notifications\NewTaskComment;
use App\Notifications\NewTaskStatus;
use App\Notifications\ProjectAssigned;
use App\Notifications\TaskAssigned;
use Arr;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class TasksController extends ProjectController
{
    public function index(Request $request, Project $project): Response
    {
        $this->authorize('viewAny', [Task::class, $project]);
//        dd($_GET);
        $sort = '-latest_activity_at';

        return Inertia::render('Project/Tasks/Tasks', [
            'activeTab' => 'tasks',
            'project' => $project,
            'search' => $request->get('search'),
//            'sort' => $request->get('sort', $sort),
            'tasks' => Inertia::lazy(fn() => $project->tasks()
                ->select('id', 'project_id', 'subject', 'description', 'priority_id', 'status_id', 'private', 'number', 'latest_activity_id', 'custom_fields')
                ->search($request->get('search'))
//                ->sortByString($request->get('sort'))
                ->with(['priority', 'status', 'latestActivity.user'])
                ->withCommentsCount()
                ->forCurrentUser()
//                ->latest('latest_activity_at')
                ->sortByString($request->get('sort', $sort))
                ->paginate($this->perPage()))
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Project $project, Task $task): Response
    {
        $this->authorize('view', $task);

        $task->load('author', 'project', 'assignees');

        $task->append(['total_logged_time', 'your_logged_time']);

        return Inertia::render('Task/Task', [
//            'project' => $project,
            'task' => $task,
            'activities' => fn() => $task->activities()
                ->with(['user', 'comment', 'media'])
                ->forCurrentUser()
                ->latest()
                ->get(),
            'users' => fn() => $project
                ->members()
                ->union($task->assignees())
                ->get(),
            'times' => fn() => $task->times()
                ->with(['author'])
                ->latest()
                ->get(),
//            'statuses' => fn() => Status::all(),
//            'priorities' => fn() => Priority::all(),
        ]);
    }

    public function create(Project $project): Modal
    {
        $this->authorize('create', [Task::class, $project]);

        return Inertia::modal('Task/TaskForm', [
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
            'task.milestone_id' => ['nullable', 'exists:milestones,id'],
            'task_start_date' => ['date'],
            'task_end_date' => ['date'],
            'task.private' => ['boolean'],
            'task.tags' => ['array'],
            'task.custom_fields' => ['array'],
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
        $validated = $request->validate([
            'activity.comment' => [],
            'activity.files' => [],
            'activity.private' => ['boolean'],
            'task.assignees' => ['array'],
            'task.subject' => ['required', 'sometimes'],
            'task.description' => [],
            'task.priority_id' => ['required', 'exists:priorities,id', 'sometimes'],
            'task.status_id' => ['required', 'exists:statuses,id', 'sometimes'],
            'task.private' => ['boolean'],
            'task.billable' => ['boolean'],
            'task.tags' => ['array'],
            'task.custom_fields' => ['array'],
            'task.start_date' => ['date', 'nullable'],
            'task.end_date' => ['date', 'nullable'],
        ], $request->all());
//dd($validated, $request->all());
        $activity = [
            'project_id' => $task->project_id,
            'type' => null,
            'details' => [
                'changed' => [],
                'attached' => [],
                'detached' => [],
            ],
            'private' => $validated['activity']['private'] ?? false,
        ];

        if ($request->input('task')) {
            $original = $task->getRawOriginal();
            $data = $request->get('task');
            if (isset($data['description']) && $data['description'] === '<p></p>') {
                $data['description'] = str_replace("<p></p>", "", $data['description']);
            }
//            dump($data, request()->all());
            $task->update($data);
            $changes = $task->getChanges();
//            dd($original,$changes);
            $changes = Arr::except($changes, ['updated_at', 'description']);

            foreach ($changes as $key => $change) {
                $oldValues = $original[$key];
                $activity['details']['changed'][$key] = [$oldValues, $change];
            }

            if(count($activity['details']['changed'])) {
                $activity['type'] = ActivityType::TASK_CHANGED;
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

//        dd($request->all());
//        if ($request->has('task.assignees')) {
            $assignees = $task->assignees()->sync(
                collect($request->get('task')['assignees'] ?? [])->pluck('id')
            );
//            dd($assignees);
            if ($assignees['attached']) {
                $activity['details']['attached']['assignees'] = $assignees['attached'];
                User::query()
//                    ->where('id', '!=', loggedUser()->id)
                    ->whereIn('id', $assignees['attached'])
                    ->each(fn(User $user) => $user->notify(
                        new TaskAssigned($task)
                    ));
            }
            if ($assignees['detached']) {
                $activity['details']['detached']['assignees'] = $assignees['detached'];
            }
//        }

        if($activity['type']) {
            $activityModel = $task->activities()->create($activity);
            assert($activityModel instanceof Activity);

            if($activity['details']['changed'] || $activity['details']['attached'] || $activity['details']['detached']) {
                if($activityModel->type === ActivityType::TASK_COMMENTED) {
                    $task->assignees()
                        ->where('id', '!=', loggedUser()->id)
                        ->get()
                        ->each(fn(User $user) => $user->notify(new NewTaskComment($activity)));
                } elseif(
                    $activity['details']->where('field', 'status_id')->isNotEmpty()
                ) {
                    $task->assignees()
                        ->where('id', '!=', loggedUser()->id)
                        ->get()
                        ->each(fn(User $user) => $user->notify(new NewTaskStatus($activity)));
                }
            }

            $files = Arr::get($request->allFiles(), 'activity.files', []);
            if ($files) {
                foreach ($files as $file) {
                    $activityModel->addMedia($file['raw'])->toMediaCollection('files');
                }
            }
        }

        $this->success('Task updated.');
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(Project $project, Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        if($task->activities()->exists() || $task->times()->exists()) {
            $task->delete();
        } else {
            $task->forceDelete();
        }

        $this->success('Task deleted.');

        return to_route('project.tasks', ['project' => $project]);
    }
}
