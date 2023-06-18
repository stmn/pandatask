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
            ->with([
                'tasks' => function ($query) {
                    $query->withCount('comments')
                        ->latest()
                        ->limit(5);
                },
                'tasks.latestActivity.user',
            ])
            ->groupBy('projects.id')
            ->orderByDesc('latest_activity_at')
            ->get();


        return Inertia::render('Dashboard', [
            'activeIndex' => 'dashboard',
            'projects' => $projects,
        ]);
    }
}
