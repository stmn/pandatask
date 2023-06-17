<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ActivitiesController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request, Project $project): Response
    {
        return Inertia::render('Project/Activities', [
            'activeTab' => 'activity',
            'project' => $project,
            'activities' => $project->activities()->with([
                'activity.author', 'task'
            ])->latest()->paginate(10),
//        'tasks' => \App\Models\Task::all(),
        ]);
    }
}
