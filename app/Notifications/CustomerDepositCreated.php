<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CustomerDepositCreated extends Notification
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
            'message' => 'New customer deposit has been created by ' . ucfirst($this->customerDeposit->createdBy->name),
            'endpoint' => '/customer-deposits/' . $this->customerDeposit->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Customer Deposit Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('New customer deposit has been created by ' . ucfirst($this->customerDeposit->createdBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}