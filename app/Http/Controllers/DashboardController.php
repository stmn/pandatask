<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    private array $hello = [
        'Hello', 'Hola', 'Witaj', 'Bonjour', 'Ciao', 'Xin chào', 'Hallo', 'Вітаю',
    ];

    public function index(Request $request): Response
    {
        return Inertia::render('Dashboard/Dashboard', [
            'activeIndex' => 'dashboard',
            'hello' => $this->hello[array_rand($this->hello)],
            'dashboard_settings' => function () {
                return loggedUser()->settings;
            },
            'projects' => function () use ($request) {
                $projects = Project::query()
                    ->select('id', 'name')
                    ->latest('latest_activity_at')
                    ->withCount('tasks')
                    ->limit(loggedUser()->settings['dashboard_projects'])
                    ->forCurrentUser()
                    ->get();

                session()->put('dashboard-projects', $projects->pluck('id')->toArray());

                return $projects;
            },
            'tasks' => Inertia::lazy(function () use ($request) {
                $dashboardProjects = session()->get('dashboard-projects');

                $tasks = [];
                foreach ($dashboardProjects as $projectId) {
                    $tasks['project_' . $projectId] = Task::query()
                        ->where('project_id', $projectId)
                        ->select('id', 'project_id', 'subject', 'status_id', 'priority_id', 'latest_activity_id', 'number')
                        ->with(['status', 'priority', 'latestActivity.user', 'project'])
                        ->withCommentsCount()
                        ->forCurrentUser()
                        ->latest('latest_activity_at')
                        ->limit(loggedUser()->settings['dashboard_tasks'])
                        ->get();
                }

                Inertia::share('tasks', $tasks);

                return $tasks;
            }),
        ]);
    }

    public function saveSettings(Request $request): void
    {
        $request->validate([
            'dashboard_projects' => 'required|integer|min:1|max:10',
            'dashboard_tasks' => 'required|integer|min:1|max:10',
        ]);

        $settings = loggedUser()->settings;
        $settings['dashboard_projects'] = $request->get('dashboard_projects');
        $settings['dashboard_tasks'] = $request->get('dashboard_tasks');
        loggedUser()->update(['settings' => $settings]);

        $this->success('Saved.');
    }
}
