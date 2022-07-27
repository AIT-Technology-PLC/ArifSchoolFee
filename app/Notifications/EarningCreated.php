<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EarningCreated extends Notification
{
    use Queueable;

    public function __construct($earning)
    {
        $this->earning = $earning;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-hand-holding-dollar',
            'message' => 'New earning has been created by ' . ucfirst($this->earning->createdBy->name),
            'endpoint' => '/earnings/' . $this->earning->id,
        ];
    }
}
