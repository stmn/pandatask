<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Closure;
use Exception;
use Illuminate\Http\Request;

abstract class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            try {
                $this->authorize('view', $request->route()->parameter('project'));
            } catch (Exception $e) {
                abort(403, 'You don\'t have access to this project.');
            }

            return $next($request);
        });
    }
}
