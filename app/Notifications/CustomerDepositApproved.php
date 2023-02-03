<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CustomerDepositApproved extends Notification
{
    use Queueable;

    public function __construct($customerDeposit)
    {
        $this->customerDeposit = $customerDeposit;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fa-solid fa-sack-dollar',
            'message' => 'Customer Deposit has been approved by ' . ucfirst($this->customerDeposit->approvedBy->name),
            'endpoint' => '/customer-deposits/' . $this->customerDeposit->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Customer Deposit Approved')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Customer Deposit has been approved by ' . ucfirst($this->customerDeposit->approvedBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}