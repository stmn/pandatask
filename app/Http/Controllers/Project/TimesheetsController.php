<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Project;

use App\Models\Project;
use App\Models\Time;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;

class TimesheetsController extends ProjectController
{
    /**
     * @throws AuthorizationException
     */
    public function index(Project $project): Response
    {
        $this->authorize('viewAny', [Time::class, $project]);

        return Inertia::render('Project/Timesheets/Timesheets', [
            'activeTab' => 'timesheets',
            'times' => Inertia::lazy(fn() => $project->times()
                ->select('id', 'project_id', 'task_id', 'comment', 'start_at', 'end_at', 'time', 'author_id')
                ->with(['task', 'author'])
                ->forCurrentUser()
                ->latest()
                ->paginate($this->perPage()))
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function form(Project $project, ?Time $time = null): Modal
    {
        if ($time) {
            $this->authorize('update', [$time]);
        } else {
            $this->authorize('create', [Time::class, $project]);
        }

        $time?->load('task');

        return Inertia::modal('Task/TaskTimeModal', [
            'project' => $project,
            'time' => $time,
            'tasks' => $project->tasks()->get(),
        ])
            ->baseRoute('project.timesheets', ['project' => $project]);
    }

    public function save(Request $request, Project $project, ?Time $time = null): void
    {
        $request->validate([
            'task' => 'required',
            'start_at' => 'required',
        ]);

        $time = Time::query()->updateOrCreate([
            'id' => $time?->id,
        ], [
            'project_id' => $project->id,
            'task_id' => $request->get('task')['id'],
            'comment' => $request->get('comment'),
            'start_at' => $request->get('start_at'),
            'end_at' => $request->get('end_at'),
            'author_id' => $request->user()->id,
        ]);

        assert($time instanceof Time);

        if ($time->end_at && $time->author->active_time_id === $time->id) {
            loggedUser()->update([
                'active_time_id' => null,
            ]);
        }
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(Project $project, Time $time): void
    {
        $this->authorize('delete', [$time]);

        $this->success('Time entry deleted.');

        $time->delete();
    }
}
