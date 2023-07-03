<?php

namespace App\Enums;

enum ActivityType: string
{
    case TASK_CREATED = 'task_created';
    case TASK_COMMENTED = 'task_commented';
    case TASK_CHANGED = 'task_changed';
    case ATTACHMENT_ADDED = 'attachment_added';

    public function getDescription(): string
    {
        return match ($this) {
            self::TASK_CREATED => 'created a task',
            self::TASK_COMMENTED => 'commented on a task',
            self::TASK_CHANGED => 'updated a task',
            self::ATTACHMENT_ADDED => 'added an attachment',
        };
    }
}
