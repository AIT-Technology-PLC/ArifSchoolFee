<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class EmployeeTransferCreated extends Notification
{
    use Queueable;

    public function __construct($employeeTransfer)
    {
        $this->employeeTransfer = $employeeTransfer;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-people-arrows-left-right',
            'message' => 'New Employee Transfer has been created by ' . ucfirst($this->employeeTransfer->createdBy->name),
            'endpoint' => '/employee-transfers/' . $this->employeeTransfer->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Employee Transfer Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('New Employee Transfer has been created by ' . ucfirst($this->employeeTransfer->createdBy->name))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}