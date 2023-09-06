<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Enums\ProjectRole;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectAssigned;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;

class ProjectsController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Project::class);

        return Inertia::render('Projects/Projects', [
            'activeIndex' => 'projects',
            'search' => $request->get('search'),
            'projects' => fn() => Project::query()
                ->selectRaw('id,name,description,latest_activity_id')
                ->search($request->get('search'))
                ->with([
                    'latestActivity.task' => fn($query) => $query->withTrashed(),
                    'latestActivity.user' => fn($query) => $query->withTrashed(),
                ])
                // with info about stats (how many tasks, how many completed, how many in progress)
//                ->withCount([
//                    'tasks as tasks_1' => fn($query) => $query->where('status_id', 1),
//                    'tasks as tasks_2' => fn($query) => $query->where('status_id', 2),
//                    'tasks as tasks_3' => fn($query) => $query->where('status_id', 3),
//                    'tasks as tasks_4' => fn($query) => $query->where('status_id', 4),
//                    'tasks as tasks_5' => fn($query) => $query->where('status_id', 5),
//                ])
                ->forCurrentUser()
                ->latest('latest_activity_at')
                ->paginate($this->perPage()),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function form(?Project $project = null): Modal
    {
        $project
            ? $this->authorize('update', $project)
            : $this->authorize('create', Project::class);

        $project?->load('teamMembers');
        $project?->load('clients');

        $fields = 'id,first_name,last_name';
        return Inertia::modal('Projects/ProjectsFormModal', [
            'project' => $project,
            'clients' => User::query()
                ->selectRaw($fields)
                ->whereHasGroupType('client')
                ->when($project, fn($query) => $query->union($project->clients()->selectRaw($fields)))
                ->get(),
            'team_members' => User::query()
                ->selectRaw($fields)
                ->whereHasGroupType('team')
                ->when($project, fn($query) => $query->union($project->teamMembers()->selectRaw($fields)))
                ->get(),
        ])
            ->baseRoute($project ? 'project.tasks' : 'projects', ['project' => $project]);
    }

    /**
     * @throws AuthorizationException
     */
    public function save(ProjectRequest $request, ?Project $project = null)
    {
        $project
            ? $this->authorize('update', $project)
            : $this->authorize('create', Project::class);

        if ($project && $request->get('delete_project')) {
            return $this->delete($project);
        }

        $project = Project::query()->updateOrCreate([
            'id' => $project?->id,
        ], [
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'client_id' => $request->get('client_id'),
            'statuses' => $request->get('statuses'),
            'priorities' => $request->get('priorities'),
//            'custom_fields' => $request->get('custom_fields', []),
        ]);

        assert($project instanceof Project);

        $clients = $project->clients()->syncWithPivotValues(
            collect($request->get('clients'))->pluck('id'),
            ['role' => ProjectRole::CLIENT]
        );

        $members = $project->teamMembers()->syncWithPivotValues(
            collect($request->get('team_members'))->pluck('id'),
            ['role' => ProjectRole::TEAM_MEMBER]
        );

        User::query()
            ->where('id', '!=', loggedUser()->id)
            ->whereIn('id', array_merge($clients['attached'], $members['attached']))
            ->get()
            ->each(fn(User $user) => $user->notify(
                new ProjectAssigned($project)
            ));

        if ($project->wasRecentlyCreated) {
            return to_route('project.tasks', ['project' => $project]);
        }

        return null;
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        if($project->tasks()->withTrashed()->exists()) {
            $project->delete();
        } else {
            $project->forceDelete();
        }

        $this->success('Project deleted.');

        return to_route('projects');
    }
}
