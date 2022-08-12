<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class BillOfMaterialCreated extends Notification
{
    use Queueable;

    public function __construct($billOfMaterial)
    {
        $this->billOfMaterial = $billOfMaterial;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'fas fa-clipboard-list',
            'message' => 'Bill Of Material has been created by ' . ucfirst($this->billOfMaterial->createdBy->name),
            'endpoint' => '/bill-of-materials/' . $this->billOfMaterial->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Bill Of Material Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Bill Of Material has been created by ' . ucfirst($this->billOfMaterial->createdBy->name))
            ->action('View', '/bill-of-materials/' . $this->billOfMaterial->id)
            ->vibrate([500, 250, 500, 250]);
    }
}