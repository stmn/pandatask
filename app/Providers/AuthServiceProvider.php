<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\Time;
use App\Models\User;
use App\Policies\ActivityPolicy;
use App\Policies\CommentPolicy;
use App\Policies\GroupPolicy;
use App\Policies\PriorityPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\StatusPolicy;
use App\Policies\TaskPolicy;
use App\Policies\TimePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Activity::class => ActivityPolicy::class,
        Comment::class => CommentPolicy::class,
        Group::class => GroupPolicy::class,
        Priority::class => PriorityPolicy::class,
        Project::class => ProjectPolicy::class,
        Status::class => StatusPolicy::class,
        Task::class => TaskPolicy::class,
        Time::class => TimePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
