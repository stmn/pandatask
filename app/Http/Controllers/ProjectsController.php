<?php

namespace App\Http\Controllers;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
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

        return Inertia::render('Projects', [
            'activeIndex' => 'projects',
            'search' => $request->get('search'),
            'projects' => fn() => Project::query()
                ->selectRaw('id,name,description,latest_activity_id')
                ->search($request->get('search'))
                ->with([
                    'latestActivity.task',
                    'latestActivity.user'
                ])
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
        return Inertia::modal('CreateProject', [
            'project' => $project,
            'clients' => User::query()
                ->selectRaw($fields)
                ->whereHasGroupType('client')
                ->union($project->clients()->selectRaw($fields))
                ->get(),
            'team_members' => User::query()
                ->selectRaw($fields)
                ->whereHasGroupType('teamm')
                ->union($project->teamMembers()->selectRaw($fields))
                ->get(),
        ])
            ->baseRoute($project ? 'project' : 'projects', ['project' => $project]);
    }

    /**
     * @throws AuthorizationException
     */
    public function save(Request $request, ?Project $project = null)
    {
        $project
            ? $this->authorize('update', $project)
            : $this->authorize('create', Project::class);

        $request->validate([
            'name' => 'required',
        ]);

        $project = Project::query()->updateOrCreate([
            'id' => $project?->id,
        ], [
            'name' => $request->name,
            'description' => $request->description,
            'client_id' => $request->client_id,
        ]);

        assert($project instanceof Project);

        $project->clients()->syncWithPivotValues(
            collect($request->clients)->pluck('id'),
            ['role' => ProjectRole::CLIENT]
        );

        $project->teamMembers()->syncWithPivotValues(
            collect($request->team_members)->pluck('id'),
            ['role' => ProjectRole::TEAM_MEMBER]
        );

        if ($project->wasRecentlyCreated) {
            return to_route('project.tasks', ['project' => $project]);
        }

        return null;
    }
}
