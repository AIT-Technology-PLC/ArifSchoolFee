<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class LeaveCreated extends Notification
{
    use Queueable;

    public function __construct($leaf)
    {
        $this->leaf = $leaf;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'user-slash',
            'message' => 'New leave has been created by ' . ucfirst($this->leaf->createdBy->name),
            'endpoint' => '/leaves/' . $this->leaf->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('leave Created')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('New leave has been created by ' . ucfirst($this->leaf->createdBy->name))
            ->action('View', '/leaves/' . $this->leaf->id)
            ->vibrate([500, 250, 500, 250]);
    }
}