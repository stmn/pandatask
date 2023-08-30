<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification
{
    protected string $name;

    /**
     * @param User|AnonymousNotifiable $notifiable
     * @return array
     */
    public function via(User|AnonymousNotifiable $notifiable): array
    {
        if($notifiable instanceof AnonymousNotifiable) {
            return ['mail'];
        }

        /** @var UserNotification $notification */
        $notification = $notifiable->userNotifications()
            ->where('name', $this->name)
            ->first();

        return $notification?->via() ?? ['mail'];
    }
}
