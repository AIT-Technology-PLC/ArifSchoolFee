<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveApproved extends Notification
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
            'message' => 'Leave has been approved by ' . ucfirst($this->leave->approvedBy->name),
            'endpoint' => '/leaves/' . $this->leave->id,
        ];
    }
}