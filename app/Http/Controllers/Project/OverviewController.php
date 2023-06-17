<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OverviewController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request, Project $project): Response
    {
        $project->load('client', 'members');

        return Inertia::render('Project/Overview', [
            'activeTab' => 'overview',
            'project' => $project,
            'activities' => $project->activities()->with([
                'activity.author', 'task'
            ])->latest()->limit(10)->get(),
            'times' => $project->times()
                ->with(['task', 'author'])
//            ->with(['latestActivity.activity.author'])
//            ->when($request->has('search'), function ($query) use ($request) {
//                $query->where('subject', 'like', '%'.$request->get('search').'%');
//            })
                ->latest()
                ->limit(10)
                ->get()
        ]);
    }
}
