<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Project;

use App\Models\Project;
use Inertia\Inertia;
use Momentum\Modal\Modal;

class OverviewController extends ProjectController
{
    public function index(Project $project): Modal
    {
        $project->load('clients', 'teamMembers');

        return Inertia::modal('Project/Overview', [
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
        ])->baseRoute('project.tasks', ['project' => $project]);
    }
}
