<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && loggedUser()->isAdmin()) {
            return $next($request);
        }

        return to_route('dashboard');
    }
}
