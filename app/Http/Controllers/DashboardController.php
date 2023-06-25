<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): Response
    {
        $projects = Project::query()
            ->leftJoin('activities', 'projects.id', '=', 'activities.project_id')
            ->selectRaw('projects.*, MAX(activities.created_at) as latest_activity_at')
            ->groupBy('projects.id')
            ->orderByDesc('latest_activity_at')
            ->get();

        $projects->each->load([
            'tasks' => function ($query) {
                return $query->withCount('comments')
                    ->latest('latest_activity_max_created_at')
                    ->withMax('latestActivity', 'created_at')
                    ->limit(5);
            },
            'tasks.latestActivity.user',
        ]);


        return Inertia::render('Dashboard', [
            'activeIndex' => 'dashboard',
            'projects' => $projects,
        ]);
    }
}
