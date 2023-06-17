<?php

namespace App\Http\Controllers;

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
            'tasks' => \App\Models\Task::query()
                ->with([
                    'project',
                    'latestActivity.activity.author',
                ])
                ->when($request->has('search'), function ($query) use ($request) {
                    $query->where('subject', 'like', '%' . $request->get('search') . '%');
                })
                ->paginate(10),
        ]);
    }
}
