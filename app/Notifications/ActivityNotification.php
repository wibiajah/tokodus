<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ActivityNotification extends Notification
{
    use Queueable;

    protected $activity;

    public function __construct($activity)
    {
        $this->activity = $activity;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => $this->activity['type'] ?? 'info',
            'message' => $this->activity['message'] ?? '',
            'actor_name' => $this->activity['actor_name'] ?? 'System', // ✅ FIX: Default value
            'icon' => $this->activity['icon'] ?? 'fas fa-info-circle',
            'url' => $this->activity['url'] ?? '#',
        ];
    }
}