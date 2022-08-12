<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PurchaseApproved extends Notification
{
    use Queueable;

    public function __construct(private $purchase)
    {
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'shopping-bag',
            'message' => 'Purchase #' . $this->purchase->code . 'is approved by' . ucfirst($this->purchase->approvedBy->name),
            'endpoint' => '/purchases/' . $this->purchase->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Purchase Approved')
            ->body('Purchase #' . $this->purchase->code . 'is approved by' . ucfirst($this->purchase->approvedBy->name))
            ->action('View', '/purchases/' . $this->purchase->id, 'shopping-bag')
            ->data(['id' => $notification->id]);
    }
}