<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    private array $hello = [
        'Hello', 'Hola', 'Witaj', 'Bonjour', 'Ciao', 'Xin chào', 'Hallo'
    ];

    public function index(Request $request): Response
    {
        return Inertia::render('Dashboard', [
            'activeIndex' => 'dashboard',
            'hello' => $this->hello[array_rand($this->hello)],
            'projects' => Inertia::lazy(function () use ($request) {
                $projects = Project::query()
                    ->latest('latest_activity_at')
                    ->limit(10)
                    ->get();

                session()->put('dashboard-projects', $projects->pluck('id')->toArray());

                return $projects;
            }),
            'tasks' => Inertia::lazy(function () use ($request) {
                $dashboardProjects = session()->get('dashboard-projects');

                $tasks = [];
                foreach ($dashboardProjects as $projectId) {
                    $tasks['project_' . $projectId] = Task::query()
                        ->where('project_id', $projectId)
                        ->select('id', 'project_id', 'subject', 'status_id', 'priority_id', 'latest_activity_id', 'number')
                        ->with(['status', 'priority', 'latestActivity.user'])
                        ->withCommentsCount()
                        ->latest('latest_activity_at')
                        ->limit(5)
                        ->get();
                }

                Inertia::share('tasks', $tasks);

                return $tasks;
            }),
        ]);
    }
}
