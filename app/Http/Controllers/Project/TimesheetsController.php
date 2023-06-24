<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Time;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TimesheetsController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request, Project $project): Response
    {
        return Inertia::render('Project/Timesheets', [
            'activeTab' => 'timesheets',
            'projects' => Project::query()->get(),
            'project' => $project,
            'times' => $project->times()
                ->with(['task', 'author'])
//            ->with(['latestActivity.activity.author'])
//            ->when($request->has('search'), function ($query) use ($request) {
//                $query->where('subject', 'like', '%'.$request->get('search').'%');
//            })
                ->orderByDesc('id')
                ->paginate(20)
        ]);
    }

    public function form(Request $request, Project $project, ?Time $time = null)
    {
        $time?->load('task');

        return Inertia::modal('CreateTimeline', [
            'project' => $project,
            'time' => $time,
            'tasks' => $project->tasks()->get(),
        ])
            ->baseRoute($project ? 'project.timesheets' : 'project.timesheets', ['project' => $project]);
    }

    public function save(Request $request, Project $project, ?Time $time = null)
    {
        $request->validate([
            'task' => 'required',
            'start_at' => 'required',
//            'end_at' => 'required',
        ]);
//dd($request->all());
        $time = Time::query()->updateOrCreate([
            'id' => $time ? $time->id : null,
        ], [
            'project_id' => $project->id,
            'task_id' => $request->task['id'],
            'comment' => $request->comment,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'author_id' => $request->user()->id,
//            'client_id' => $request->client_id,
        ]);

//        $time->members()->sync(
//            collect($request->members)->pluck('id')
//        );

//        if ($time->wasRecentlyCreated) {
//            return to_route('project.timesheets', ['project' => $project]);
//        }
    }
}
