<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class ActivitiesController extends Controller
{
    public function index(Project $project): Response
    {
        return Inertia::render('Project/Activities', [
            'activeTab' => 'activity',
            'projects' => fn() => Project::query()->get(),
            'project' => $project,
            'activities' => fn() => $project
                ->activities()
                ->with(['task', 'user', 'comment'])
                ->latest()
                ->paginate($this->perPage())
                ->appends('details'),
        ]);
    }
}
