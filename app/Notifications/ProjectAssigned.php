<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectAssigned extends BaseNotification
{
    use Queueable;

    protected string $name = 'project-assigned';

    public Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New project assigned to you.')
            ->markdown('mail.project-assigned', [
                'project' => $this->project,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'project' => $this->project,
        ];
    }
}
