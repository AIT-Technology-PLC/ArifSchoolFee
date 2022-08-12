<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ReturnPrepared extends Notification
{
    use Queueable;

    public function __construct($return)
    {
        $this->return = $return;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'arrow-alt-circle-left',
            'message' => 'Approval request for return voucher prepared by ' . ucfirst($this->return->createdBy->name),
            'endpoint' => '/returns/' . $this->return->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Returned Prepared')
            ->body('Approval request for return voucher prepared by ' . ucfirst($this->return->createdBy->name))
            ->action('View', '/returns/' . $this->return->id, 'arrow-alt-circle-left')
            ->data(['id' => $notification->id]);
    }
}