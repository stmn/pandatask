<?php

namespace App\Console;

use App\Jobs\SendUpcomingDeadlineNotifications;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // upcoming deadlines in week
        $schedule->call(function () {
            (new SendUpcomingDeadlineNotifications(
                SendUpcomingDeadlineNotifications::REMINDER_TYPE_WEEKLY
            ))->handle();
        })->mondays()->at('08:00');

        // upcoming deadlines in day
        $schedule->call(function () {
            (new SendUpcomingDeadlineNotifications(
                SendUpcomingDeadlineNotifications::REMINDER_TYPE_DAILY
            ))->handle();
        })->dailyAt('08:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
