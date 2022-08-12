<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AttendanceApproved extends Notification
{
    use Queueable;

    public function __construct($attendance)
    {
        $this->attendance = $attendance;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'clipboard-user',
            'message' => 'Attendance has been approved by ' . ucfirst($this->attendance->approvedBy->name),
            'endpoint' => '/attendances/' . $this->attendance->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Attendance Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Attendance has been approved by ' . ucfirst($this->attendance->approvedBy->name))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
