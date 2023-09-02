<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Collection;

class UpcomingDailyDeadline extends BaseNotification
{
    use Queueable;

    protected string $name = 'upcoming-daily-deadline';

    public Collection $tasks;

    public function __construct(Collection $tasks)
    {
        $this->tasks = $tasks;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Upcoming daily deadlines (' . $this->tasks->count() . ') ⏰')
            ->markdown('mail.upcoming-daily-deadline', [
                'tasks' => $this->tasks,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tasks' => $this->tasks,
        ];
    }
}
