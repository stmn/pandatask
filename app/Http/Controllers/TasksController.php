<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TasksController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Tasks', [
            'activeIndex' => 'tasks',
            'search' => $request->get('search'),
            'tasks' => Inertia::lazy(fn() => Task::query()
                ->select('id', 'project_id', 'subject', 'status_id', 'priority_id', 'latest_activity_id', 'number')
                ->search($request->get('search'))
                ->with([
                    'status', 'priority', 'latestActivity.user', 'project',
                ])
                ->withCommentsCount()
                ->latest('latest_activity_at')
                ->paginate($this->perPage())),
        ]);
    }
}
