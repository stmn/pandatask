<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TasksController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Tasks', [
            'activeIndex' => 'tasks',
            'search' => $request->get('search'),
            'tasks' => Task::query()
                ->with([
                    'project',
                    'latestActivity.activity',
                    'latestActivity.user',
                    'status', 'priority',
                ])
                ->withCount('comments')
                ->when($request->has('search'), function ($query) use ($request) {
                    $query->where('subject', 'like', '%' . $request->get('search') . '%');
                })
                ->latest('latest_activity_max_created_at')
                ->withMax('latestActivity', 'created_at')
                ->paginate($this->perPage()),
        ]);
    }
}
