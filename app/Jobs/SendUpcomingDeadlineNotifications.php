<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use App\Notifications\UpcomingDailyDeadline;
use App\Notifications\UpcomingWeeklyDeadline;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;

class SendUpcomingDeadlineNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const REMINDER_TYPE_WEEKLY = 'weekly';
    const REMINDER_TYPE_DAILY = 'daily';

    public function __construct(public string $type)
    {
        if (!in_array($type, [self::REMINDER_TYPE_DAILY, self::REMINDER_TYPE_WEEKLY])) {
            throw new InvalidArgumentException('Invalid reminder type');
        }
    }

    public function handle(): void
    {
        $users = [];

        if ($this->type === self::REMINDER_TYPE_WEEKLY) {
            $query = Task::query()
                ->whereDate('end_date', '>=', now()->startOfWeek())
                ->whereDate('end_date', '<=', now()->endOfWeek());
        } else {
            $query = Task::query()->whereDate('end_date', today());
        }

        $query
            ->with('assignees')
            ->get()
            ->each(function (Task $task) use (&$users) {
                $task->assignees->each(function ($user) use ($task, &$users) {
                    if (!isset($users[$user->id])) {
                        $users[$user->id] = collect();
                    }

                    $users[$user->id]->push($task);
                });
            });

        foreach ($users as $userId => $tasks) {
            $user = User::query()->find($userId);
            assert($user instanceof User);

            if ($this->type === self::REMINDER_TYPE_WEEKLY) {
                $user->notify(new UpcomingWeeklyDeadline($tasks));
            } else {
                $user->notify(new UpcomingDailyDeadline($tasks));
            }
        }
    }
}
