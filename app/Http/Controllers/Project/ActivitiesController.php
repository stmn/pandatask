<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ActivitiesController extends Controller
{
    public function index(Request $request, Project $project): Response
    {
        return Inertia::render('Project/Activities', [
            'activeTab' => 'activity',
            'projects' => Project::query()->get(),
            'project' => $project,
            'activities' => $project->activities()
                ->with(['task', 'user'])
                ->latest()
                ->paginate($this->perPage()),
        ]);
    }
}
