<?php

namespace App\Notifications;

use App\Models\Activity;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class NewTaskComment extends BaseNotification
{
    use Queueable;

    protected string $name = 'new-task-comment';

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
            ->subject('New comment on task 💬 - ' . $this->task->subject)
            ->markdown('mail.new-task-comment', [
                'activity' => $this->activity,
                'task' => $this->task,
            ]);
    }
}
