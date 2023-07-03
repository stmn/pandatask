<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): Response
    {

//        $projectsOrder = session()->get('projectsOrder2');
//        dd(123);
//        Cookie::forget('projectsOrder');
//        $projectsOrder = false;//Cookie::get('projectsOrder');

        return Inertia::render('Dashboard', [
            'activeIndex' => 'dashboard',
            'projects' => Inertia::lazy(function () use ($request) {
                $projects = Project::query()
//                    ->when($projectsOrder, fn($query) => $query->orderByRaw('FIELD(projects.id, ' . $projectsOrder . ')'))
//                    ->when(!$projectsOrder, fn($query) => $query->orderByDesc('latest_activity_at'))
                    ->latest('latest_activity_at')
                    ->limit(10)
                    ->get();

                session()->put('dashboard-projects', $projects->pluck('id')->toArray());

                return $projects;
            }),
            'tasks' => Inertia::lazy(function () use ($request) {
                $dashboardProjects = session()->get('dashboard-projects');
                // 1. This helps to keep the order of the projects in the dashboard when status/priority are changed
//                $projectsOrder = $request->header('Projects-Order');

//dd($projectsOrder);
//                dd($projectsOrder);
//                $projects = Project::query()
//                    // 2. Same as above
//                    ->when($dashboardProjects, fn($query) => $query->orderByRaw('FIELD(projects.id, ' . $dashboardProjects . ')'))
//                    ->when(!$dashboardProjects, fn($query) => $query->orderByDesc('latest_activity_at'))
//                    ->limit(10)
//                    ->get();
//
//                // 3. Same as above
//                Inertia::share('projectsOrder', $projects->pluck('id'));

//                session()->put('projectsOrder2', $projects->pluck('id')->implode(','));
//                Cookie::forget('projectsOrder');
//
//                $projects->each->load([
//                    'tasks' => function ($query) {
//                        return $query
//                            ->select('id', 'project_id', 'subject', 'status_id', 'priority_id', 'latest_activity_id', 'number')
//                            ->withCount('comments')
//                            ->latest('latest_activity_at')
//                            ->with(['status', 'priority', 'latestActivity.user'])
//                            ->limit(5);
//                    },
//                ]);


//                return $projects;


//                $aaa = array_map(function($projectId){
//                    return Task::query()
//                        ->select('id', 'project_id', 'subject', 'status_id', 'priority_id', 'latest_activity_id', 'number')
//                        ->withCount('comments')
//                        ->latest('latest_activity_at')
//                        ->with(['status', 'priority', 'latestActivity.user'])
//                        ->where('project_id', $projectId)
//                        ->orderByDesc('latest_activity_at')
//                        ->limit(2)
//                        ->get();
//                }, $dashboardProjects);
//dd(count($aaa));
                $aaa = [];
                foreach ($dashboardProjects as $key => $projectId) {
                    $aaa['project_'.$projectId] = Task::query()
                        ->select('id', 'project_id', 'subject', 'status_id', 'priority_id', 'latest_activity_id', 'number')
                        ->withCount(['activities as comments_count' => fn($query) => $query->where('type', '=', ActivityType::TASK_COMMENTED)])
                        ->latest('latest_activity_at')
                        ->with(['status', 'priority', 'latestActivity.user'])
                        ->where('project_id', $projectId)
                        ->orderByDesc('latest_activity_at')
                        ->limit(5)
                        ->get();
                }

                Inertia::share('tasks', $aaa);

                return $aaa;
            }),
        ]);
    }
}
