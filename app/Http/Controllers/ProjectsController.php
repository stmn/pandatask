<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;

class ProjectsController extends Controller
{
    public function index(Request $request): Response
    {
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
                ->latest('latest_activity_at')
                ->paginate($this->perPage()),
        ]);
    }

    public function form(?Project $project = null): Modal
    {
        $project?->load('members');

        return Inertia::modal('CreateProject', [
            'project' => $project,
            'clients' => User::query()->get()->map(function ($user) {
                return [
                    'value' => $user->id,
                    'label' => $user->full_name,
                ];
            }),
            'users' => User::query()->selectRaw('id,first_name,last_name')->get(),
        ])
            ->baseRoute($project ? 'project' : 'projects', ['project' => $project]);
    }

    public function save(Request $request, ?Project $project = null)
    {
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

        $project->members()->sync(
            collect($request->members)->pluck('id')
        );

        if ($project->wasRecentlyCreated) {
            return to_route('project.tasks', ['project' => $project]);
        }

        return null;
    }
}
