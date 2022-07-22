<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveCreated extends Notification
{
    use Queueable;

    public function __construct($leave)
    {
        $this->leave = $leave;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'user-slash',
            'message' => 'New leave has been created by ' . ucfirst($this->leave->createdBy->name),
            'endpoint' => '/leaves/' . $this->leave->id,
        ];
    }
}