<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PayrollPaid extends Notification
{
    use Queueable;

    public function __construct($payroll)
    {
        $this->payroll = $payroll;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fa-solid fa-coins',
            'message' => 'Payroll has been paid by ' . ucfirst($this->payroll->paidBy->name),
            'endpoint' => '/payrolls/' . $this->payroll->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Payroll Paid')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Payroll has been paid by ' . ucfirst($this->payroll->paidBy->name))
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}