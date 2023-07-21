<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TasksController extends Controller
{
    /**
     * @throws AuthorizationException
     * @noinspection PhpMultipleClassDeclarationsInspection
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Task::class);

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
                ->forCurrentUser()
                ->latest('latest_activity_at')
                ->paginate($this->perPage())),
        ]);
    }
}
