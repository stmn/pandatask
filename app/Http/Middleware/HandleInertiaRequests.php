<?php

namespace App\Http\Middleware;

use App\Enums\ActivityType;
use App\Models\Project;
use App\QueryBuilders\ActivityQueryBuilder;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => function () use ($request) {
                $user = loggedUser();

                if ($user) {
                    $user->load('activeTime.task');
                    $user?->activeTime?->task?->loadCount([
                        'activities as comments_count' => fn(ActivityQueryBuilder $query) => $query->whereType(ActivityType::TASK_COMMENTED)
                    ]);

                    return [
                        'user' => $user,
                        'impersonated' => session()->has('impersonated'),
                        'groups' => $user->groups()->get()->keyBy('type'),
                        'permissions' => [
                            'create projects' => $user->can('create', Project::class),
                        ]
                    ];
                }

                return null;
            },
            'flash' => [
                'message' => $request->session()->get('message'),
            ],
//            'ziggy' => function () use ($request) {
//                return array_merge((new Ziggy)->toArray(), [
//                    'location' => $request->url(),
//                ]);
//            },
            'csrf_token' => csrf_token(),
        ]);
    }
}
