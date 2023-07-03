<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
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
            'tasks' => Inertia::lazy(fn() => Task::query()
                ->with([
                    'project',
                    'latestActivity.user',
                    'status',
                    'priority',
                ])
//                ->withCount('comments')
                ->withCount(['activities as comments_count' => fn($query) => $query->where('type', '=', ActivityType::TASK_COMMENTED)])
                ->when($request->has('search'), function ($query) use ($request) {
                    $query->where('subject', 'like', '%' . $request->get('search') . '%');
                })
                ->latest('latest_activity_at')
//                ->where('id', -1)
                ->paginate($this->perPage())),
        ]);
    }
}
