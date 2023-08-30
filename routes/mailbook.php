<?php

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\NewTaskComment;
use App\Notifications\NewTaskStatus;
use App\Notifications\ProjectAssigned;
use App\Notifications\TaskAssigned;
use App\Notifications\UpcomingDailyDeadline;
use App\Notifications\UpcomingWeeklyDeadline;
use App\Notifications\UserCreated;
use Xammie\Mailbook\Facades\Mailbook;

$user = User::first();//\App\Models\User::factory()->create();

Mailbook::to($user)->group(function () use($user) {
    Mailbook::add(new UserCreated($user, '123456'));

    $project = Project::inRandomOrder()->first();
    if($project) {
        Mailbook::add(new ProjectAssigned($project));
    }

    $task = Task::inRandomOrder()->first();
    if($task) {
        Mailbook::add(new TaskAssigned($task));
    }

    $tasks = Task::inRandomOrder()->limit(5)->get();
    if($tasks->count()) {
        Mailbook::add(new UpcomingDailyDeadline($tasks));
        Mailbook::add(new UpcomingWeeklyDeadline($tasks));
    }

    $activity = Activity::whereType(ActivityType::TASK_COMMENTED)->inRandomOrder()->first();
    if ($activity) {
        Mailbook::add(new NewTaskComment($activity));
    }

    $activity = Activity::whereType(ActivityType::TASK_CHANGED)->inRandomOrder()->first();
    if ($activity) {
        Mailbook::add(new NewTaskStatus($activity));
    }
});
