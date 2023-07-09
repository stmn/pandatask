<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Time;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;

class TimesheetsController extends Controller
{
    public function index(Project $project): Response
    {
        return Inertia::render('Project/Timesheets', [
            'activeTab' => 'timesheets',
            'projects' => fn() => Project::query()->get(),
            'project' => fn() => $project,
            'times' => Inertia::lazy(fn() => $project->times()
                ->with(['task', 'author'])
                ->latest('created_at')
                ->paginate($this->perPage()))
        ]);
    }

    public function form(Project $project, ?Time $time = null): Modal
    {
        $time?->load('task');

        return Inertia::modal('CreateTimeline', [
            'project' => $project,
            'time' => $time,
            'tasks' => $project->tasks()->get(),
        ])
            ->baseRoute('project.timesheets', ['project' => $project]);
    }

    public function save(Request $request, Project $project, ?Time $time = null): void
    {
        $request->validate([
            'task' => 'required',
            'start_at' => 'required',
        ]);

        Time::query()->updateOrCreate([
            'id' => $time?->id,
        ], [
            'project_id' => $project->id,
            'task_id' => $request->task['id'],
            'comment' => $request->comment,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'author_id' => $request->user()->id,
        ]);
    }
}
