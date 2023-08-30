<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssigned extends BaseNotification
{
    use Queueable;

    protected string $name = 'task-assigned';

    public Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New task assigned to you - ' . $this->task->subject)
            ->markdown('mail.task-assigned', [
                'task' => $this->task,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task' => $this->task,
        ];
    }
}
