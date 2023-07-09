<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class OverviewController extends Controller
{
    public function index(Project $project): Response
    {
        $project->load('client', 'members');

        return Inertia::render('Project/Overview', [
            'activeTab' => 'overview',
            'project' => $project,
            'projects' => fn() => Project::query()
                ->select('id', 'name')
                ->get(),
            'activities' => fn() => $project->activities()
                ->with(['task', 'user'])
                ->latest()
                ->limit(10)
                ->get(),
            'times' => fn() => $project->times()
                ->with(['task', 'author'])
                ->latest()
                ->limit(10)
                ->get()
        ]);
    }
}
