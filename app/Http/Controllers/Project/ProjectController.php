<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

abstract class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            /** @var Project $project */
            $project = $request->route()->parameter('project');

            try {
                $this->authorize('view', $project);
            } catch (Exception) {
                abort(403, 'You don\'t have access to this project.');
            }

            Inertia::share('project', fn() => $project);

            Inertia::share('projects', fn() => Project::query()
                ->select('id', 'name')
                ->forCurrentUser()
                ->get());

            Inertia::share('milestones', fn() => $project->milestones()
                ->select('id', 'name')
                ->get());

            return $next($request);
        });

        parent::__construct();
    }
}
