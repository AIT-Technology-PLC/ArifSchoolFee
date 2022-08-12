<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class EmployeeTransferApproved extends Notification
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
            'message' => 'Employee Transfer has been approved by ' . ucfirst($this->employeeTransfer->approvedBy->name),
            'endpoint' => '/employee-transfers/' . $this->employeeTransfer->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Employee Transfer Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Employee Transfer has been approved by ' . ucfirst($this->employeeTransfer->approvedBy->name))
            ->action('View', '/employee-transfers/' . $this->employeeTransfer->id)
            ->vibrate([500, 250, 500, 250]);
    }
}