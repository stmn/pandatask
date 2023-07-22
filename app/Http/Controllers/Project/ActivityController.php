<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Project;

use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class ActivityController extends ProjectController
{
    public function index(Project $project): Response
    {
        return Inertia::render('Project/Activity', [
            'activeTab' => 'activity',
            'project' => $project,
            'projects' => fn() => Project::query()
                ->select('id', 'name')
                ->get(),
            'activities' => fn() => $project
                ->activities()
                ->select('id', 'project_id', 'task_id', 'user_id', 'comment_id', 'created_at', 'type')
                ->with(['task', 'user', 'comment'])
                ->latest()
                ->paginate($this->perPage())
                ->appends('details'),
        ]);
    }
}
