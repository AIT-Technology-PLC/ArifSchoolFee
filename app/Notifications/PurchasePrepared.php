<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PurchasePrepared extends Notification
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
            'message' => 'Approval request for purchase request prepared by ' . ucfirst($this->purchase->createdBy->name),
            'endpoint' => '/purchases/' . $this->purchase->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Purchase Prepared')
            ->body('Approval request for purchase request prepared by ' . ucfirst($this->purchase->createdBy->name))
            ->action('View', '/purchases/' . $this->purchase->id, 'shopping-bag')
            ->data(['id' => $notification->id]);
    }
}