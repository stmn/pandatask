<?php

namespace App\Notifications;

use App\Models\Activity;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class NewTaskStatus extends BaseNotification
{
    use Queueable;

    protected string $name = 'new-task-status';

    public Activity $activity;
    public Task $task;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
        $this->task = $activity->task;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Task status changed 📝 - ' . $this->task->subject)
            ->markdown('mail.new-task-status', [
                'activity' => $this->activity,
                'task' => $this->task,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'activity' => $this->activity,
            'task' => $this->task,
        ];
    }
}
