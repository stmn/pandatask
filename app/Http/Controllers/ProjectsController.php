<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectsController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Projects', [
            'activeIndex' => 'projects',
            'search' => $request->get('search'),
            'projects' => Project::query()
                ->with([
                    'latestActivity.activity.author',
                    'latestActivity.task'
                ])
                ->when($request->has('search'), function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->get('search') . '%');
                })
                ->paginate(6),
        ]);
    }

    public function form(Request $request, ?Project $project = null){
        $project?->load('members');

        return Inertia::modal('CreateProject', [
            'project' => $project,
            'clients' => User::query()->get()->map(function ($user) {
                return [
                    'value' => $user->id,
                    'label' => $user->full_name,
                ];
            }),
            'users' => User::query()->get(),
        ])
            ->baseRoute($project ? 'project.overview' : 'projects', ['project' => $project]);
    }

    public function save(Request $request, ?Project $project = null){
        $request->validate([
            'name' => 'required',
        ]);

        $project = Project::query()->updateOrCreate([
            'id' => $project ? $project->id : null,
        ], [
            'name' => $request->name,
            'description' => $request->description,
            'client_id' => $request->client_id,
        ]);

        $project->members()->sync(
            collect($request->members)->pluck('id')
        );

        if($project->wasRecentlyCreated) {
            return to_route('project.tasks', ['project' => $project]);
        }
    }
}
