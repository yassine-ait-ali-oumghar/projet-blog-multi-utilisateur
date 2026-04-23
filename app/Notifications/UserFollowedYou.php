<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserFollowedYou extends Notification
{
    use Queueable;

    public function __construct(private User $actor)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'follow',
            'message' => "{$this->actor->name} a commence a vous suivre.",
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
        ];
    }
}
