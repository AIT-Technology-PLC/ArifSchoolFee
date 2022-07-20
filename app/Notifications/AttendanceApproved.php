<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AttendanceApproved extends Notification
{
    use Queueable;

    public function __construct($attendance)
    {
        $this->attendance = $attendance;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'clipboard-user',
            'message' => 'Attendance has been approved by ' . ucfirst($this->attendance->approvedBy->name),
            'endpoint' => '/attendances/' . $this->attendance->id,
        ];
    }
}